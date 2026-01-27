<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();

            $table->float('temperature');
            $table->float('air_humidity');
            $table->float('soil_moisture');

            // status dari Arduino
            $table->unsignedTinyInteger('fan_pwm')->default(0);
            $table->unsignedTinyInteger('pump_pwm')->default(0);
            $table->string('uv_lamp', 10);  // redup / terang

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
