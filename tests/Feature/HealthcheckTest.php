<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthcheckTest extends TestCase
{
    public function test_la_app_responde(): void
    {
        $response = $this->get('/');
        $this->assertGreaterThanOrEqual(200, $response->getStatusCode());
    }
}
