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

    public function test_index_response_time()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $startTime = microtime(true);
        $response = $this->getJson('/api/translations');
        $endTime = microtime(true);

        $duration = ($endTime - $startTime) * 1000; // ms

        $response->assertStatus(200);

        echo "\nIndex Endpoint Duration: " . round($duration, 2) . "ms\n";

        $this->assertTrue($duration < 200, "Index response time too slow: {$duration}ms");
    }

    public function test_export_response_time()
    {
        $startTime = microtime(true);
        $response = $this->getJson('/api/export?locale=en');
        $endTime = microtime(true);

        $duration = ($endTime - $startTime) * 1000; // ms

        $response->assertStatus(200);

        echo "\nExport Endpoint Duration: " . round($duration, 2) . "ms\n";

        $this->assertTrue($duration < 500, "Export response time too slow: {$duration}ms");
    }
}
