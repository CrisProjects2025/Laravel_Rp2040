<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'measured_at',
    ];

    protected $casts = [
        'measured_at' => 'datetime',
    ];
}


