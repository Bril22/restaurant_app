<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use App\Models\RestaurantSchedule;

class RestaurantScheduleSeeder extends Seeder
{
    public function run()
    {
        $restaurants = [
            [
                'name' => 'Kushi Tsuru',
                'schedule' => [
                    ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 'open' => '11:30:00', 'close' => '21:00:00'],
                ]
            ],
            [
                'name' => 'Osakaya Restaurant',
                'schedule' => [
                    ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Sunday'], 'open' => '11:30:00', 'close' => '21:00:00'],
                    ['days' => ['Friday', 'Saturday'], 'open' => '11:30:00', 'close' => '21:30:00'],
                ]
            ],
            [
                'name' => 'The Stinking Rose',
                'schedule' => [
                    ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Sunday'], 'open' => '11:30:00', 'close' => '22:00:00'],
                    ['days' => ['Friday', 'Saturday'], 'open' => '11:30:00', 'close' => '23:00:00'],
                ]
            ],
            [
                'name' => 'McCormick & Kuleto\'s',
                'schedule' => [
                    ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Sunday'], 'open' => '11:30:00', 'close' => '22:00:00'],
                    ['days' => ['Friday', 'Saturday'], 'open' => '11:30:00', 'close' => '23:00:00'],
                ]
            ],
            [
                'name' => 'Mifune Restaurant',
                'schedule' => [
                    ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 'open' => '11:00:00', 'close' => '22:00:00'],
                ]
            ],
            [
                'name' => 'The Cheesecake Factory',
                'schedule' => [
                    ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday'], 'open' => '11:00:00', 'close' => '23:00:00'],
                    ['days' => ['Friday', 'Saturday'], 'open' => '11:00:00', 'close' => '00:30:00'],
                    ['days' => ['Sunday'], 'open' => '10:00:00', 'close' => '23:00:00'],
                ]
            ]
        ];

        foreach ($restaurants as $data) {
            $restaurant = Restaurant::create(['id' => (string) Str::uuid(), 'name' => $data['name']]);

            foreach ($data['schedule'] as $schedule) {
                foreach ($schedule['days'] as $day) {
                    RestaurantSchedule::create([
                        'restaurant_id' => $restaurant->id,
                        'day_of_week' => $day,
                        'open_time' => $schedule['open'],
                        'close_time' => $schedule['close'],
                    ]);
                }
            }
        }
    }
}

