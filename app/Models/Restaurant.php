<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Restaurant extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'restaurants';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'opening_hours',
    ];

    protected $casts = [
        'opening_hours' => 'array',
    ];
}
