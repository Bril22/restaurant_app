<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantSchedule extends Model
{
    use HasFactory;

    protected $table = 'restaurant_schedules';

    protected $fillable = [
        'restaurant_id',
        'day_of_week',
        'open_time',
        'close_time',
    ];
    protected $hidden = ['created_at'];
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }
}
