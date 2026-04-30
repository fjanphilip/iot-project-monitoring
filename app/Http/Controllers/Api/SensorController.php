<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'air_humidity' => 'required|numeric',
            'soil_moisture' => 'required|numeric',
            'fan_pwm' => 'required|integer|min:0|max:255',
            'pump_pwm' => 'required|integer|min:0|max:255',
            'uv_lamp' => 'required|string'
        ]);

        Sensor::create($validated);

        return response()->json([
            'message' => 'Data stored',
            'data' => $validated
        ], 201);
    }

    public function latest()
    {
        return response()->json([
            'data' => Sensor::latest()->first()
        ]);
    }
}
