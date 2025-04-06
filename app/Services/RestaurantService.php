<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RestaurantService
{
    protected $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllRestaurants(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Restaurant::with('schedules')->select('restaurants.*');

        if (!empty($filters['name'])) {
            $query->where('restaurants.name', 'ILIKE', "%{$filters['name']}%");
        }

        if (!empty($filters['day']) || !empty($filters['time'])) {
            $query->whereHas('schedules', function ($q) use ($filters) {
                if (!empty($filters['day'])) {
                    $q->where('day_of_week', $filters['day']);
                }

                if (!empty($filters['time'])) {
                    $searchTime = date("H:i:s", strtotime($filters['time']));

                    $q->where(function ($q) use ($searchTime) {
                        $q->whereRaw('?::time BETWEEN open_time AND close_time', [$searchTime])
                            ->orWhere(function ($q) use ($searchTime) {
                                // handle time after midnight
                                $q->whereRaw('close_time < open_time')
                                    ->where(function ($q) use ($searchTime) {
                                    $q->whereRaw('open_time <= ?::time OR close_time >= ?::time', [$searchTime, $searchTime]);
                                });
                            });
                    });
                }
            });
        }

        $sortBy = $filters['sort'] ?? 'updated_at';
        $order = $filters['order'] ?? 'desc';

        return $query->orderBy("restaurants.$sortBy", $order)->paginate($perPage);
    }


    /**
     * @param array $data
     * @return Restaurant
     */
    public function createRestaurant(array $data): Restaurant
    {
        $restaurant = $this->restaurant->create([
            'id' => Str::uuid(),
            'name' => $data['name']
        ]);

        if (!empty($data['schedules'])) {
            $this->createSchedules($restaurant, $data['schedules']);
        }

        return $restaurant->load('schedules');
    }

    /**
     * @param string $id
     * @param array $data
     * @return Restaurant|null
     */
    public function updateRestaurant(string $id, array $data): ?Restaurant
    {
        $restaurant = $this->getRestaurantById($id);

        if (!$restaurant) {
            return null;
        }

        $restaurant->update([
            'name' => $data['name'],
        ]);

        if (!empty($data['schedules']) && is_array($data['schedules'])) {
            $this->updateSchedules($restaurant, $data['schedules']);
        }

        return $restaurant->load('schedules');
    }

    /**
     * @param string $id
     * @return bool
     */
    public function deleteRestaurant(string $id): bool
    {
        $restaurant = $this->getRestaurantById($id);

        if (!$restaurant) {
            return false;
        }

        return $restaurant->delete();
    }

    /**
     * @return Collection
     */
    public function getAllRestaurantsWithoutPagination(): Collection
    {
        return $this->restaurant
            ->orderBy('name')
            ->get();
    }

    /**
     * @param string $id
     * @return Restaurant|null
     */
    public function getRestaurantById(string $id): ?Restaurant
    {
        return $this->restaurant->find($id);
    }

    private function createSchedules(Restaurant $restaurant, array $schedules): void
    {
        foreach ($schedules as $schedule) {
            RestaurantSchedule::create([
                'restaurant_id' => $restaurant->id,
                'day_of_week' => $schedule['day_of_week'],
                'open_time' => $schedule['open_time'],
                'close_time' => $schedule['close_time'],
            ]);
        }
    }

    private function updateSchedules(Restaurant $restaurant, array $schedules): void
    {
        $restaurant->schedules()->delete();

        foreach ($schedules as $schedule) {
            $restaurant->schedules()->create([
                'day_of_week' => $schedule['day_of_week'],
                'open_time' => $schedule['open_time'],
                'close_time' => $schedule['close_time'],
            ]);
        }
    }
}