<?php

namespace Tests\Feature;

use Tests\TestCase;

class RedirectTest extends TestCase
{
    public function test_unauthenticated_request_without_json_header_returns_json_error_not_redirect()
    {
        $response = $this->get('/api/user');

        if ($response->status() === 405 || $response->status() === 302) {
             echo "\nReproduction confirmed: Status is " . $response->status() . "\n";
        }

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthenticated.']);
    }
}
