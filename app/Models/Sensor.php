<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    // Mass assignable attributes
    protected $fillable = [
        'temperature',    // suhu °C
        'air_humidity',   // kelembaban udara %
        'soil_moisture',  // kelembaban tanah %
        'fan_pwm',        // kipas PWM 0-255
        'pump_pwm',       // pompa PWM 0-255
        'uv_lamp'         // status UV lamp: ON / OFF
    ];

    // Optional: jika ingin otomatis cast data tertentu
    protected $casts = [
        'temperature' => 'float',
        'air_humidity' => 'float',
        'soil_moisture' => 'float',
        'fan_pwm' => 'integer',
        'pump_pwm' => 'integer',
        'uv_lamp' => 'string',
    ];
}
