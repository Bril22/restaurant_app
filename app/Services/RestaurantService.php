<?php

namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

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
        $query = $this->restaurant->query();

        if (!empty($filters['name'])) {
            $query->where('name', 'ILIKE', "%{$filters['name']}%");
        }

        if (!empty($filters['day'])) {
            $query->where(function($q) use ($filters) {
                $q->whereRaw("opening_hours::text ILIKE ?", ["%{$filters['day']}%"]);
            });
        }

        if (!empty($filters['time'])) {
            $query->where(function($q) use ($filters) {
                $q->whereRaw("opening_hours::text ILIKE ?", ["%{$filters['time']}%"]);
            });
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    /**
     * @param array $data
     * @return Restaurant
     */
    public function createRestaurant(array $data): Restaurant
    {
        return $this->restaurant->create([
            'id' => Str::uuid(),
            'name' => $data['name'],
            'opening_hours' => $data['opening_hours']
        ]);
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
            'opening_hours' => $data['opening_hours']
        ]);

        return $restaurant;
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
}