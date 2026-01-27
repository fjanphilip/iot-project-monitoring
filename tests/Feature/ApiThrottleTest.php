<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_throttling()
    {
        // Make first request
        $response = $this->get('/api/sensor/latest');
        $response->assertStatus(200);

        // Make second request immediately - should be throttled
        $response = $this->get('/api/sensor/latest');
        $response->assertStatus(429); // Too Many Requests
    }
}
