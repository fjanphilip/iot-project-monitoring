<?php

namespace App\Exports;

use App\Models\Sensor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SensorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sensor::select(
            'temperature',
            'humidity',
            'weight',
            'lamp',
            'fan',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Temperature (°C)',
            'Humidity (%)',
            'Weight (gram)',
            'Lamp Status',
            'Fan Status',
            'Created At',
        ];
    }
}
