<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Response;

class SensorExportController extends Controller
{
    public function exportCsv()
    {
        $filename = 'sensor-data.csv';

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'Temperature',
                'Air Humidity',
                'Soil Moisture',
                'Fan PWM',
                'Pump PWM',
                'UV Lamp',
                'Created At'
            ]);

            // Ambil semua data terbaru
            foreach (Sensor::latest()->get() as $sensor) {
                fputcsv($handle, [
                    $sensor->temperature,
                    $sensor->air_humidity,
                    $sensor->soil_moisture,
                    $sensor->fan_pwm,   // sebelumnya fan
                    $sensor->pump_pwm,  // tambahan pump
                    $sensor->uv_lamp,
                    $sensor->created_at,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
