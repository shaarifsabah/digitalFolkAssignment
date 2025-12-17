<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Benchmark;

class PerformanceTest extends TestCase
{
    // Do NOT use RefreshDatabase since we want to test against the seeded db

    public function test_index_response_time()
    {
        // Ensure user exists and has token
        \App\Models\User::updateOrCreate(
            ['email' => 'test@test.com'],
            ['name' => 'Test', 'password' => 'password', 'api_token' => 'valid_token']
        );

        $startTime = microtime(true);
        $response = $this->withHeaders(['Authorization' => 'Bearer valid_token'])
                         ->getJson('/api/translations');
        $endTime = microtime(true);

        $duration = ($endTime - $startTime) * 1000; // ms

        echo "\nIndex Endpoint Duration: " . round($duration, 2) . "ms";

        $response->assertStatus(200);
        $this->assertTrue($duration < 200, "Index response time too slow: {$duration}ms");
    }

    public function test_export_response_time()
    {
        $startTime = microtime(true);
        $response = $this->withHeaders(['Authorization' => 'Bearer valid_token'])
                         ->getJson('/api/export?locale=en');
        $endTime = microtime(true);

        $duration = ($endTime - $startTime) * 1000; // ms

        echo "\nExport Endpoint Duration: " . round($duration, 2) . "ms";

        $response->assertStatus(200);
        // Requirement < 500ms
        $this->assertTrue($duration < 500, "Export response time too slow: {$duration}ms");
    }
}
