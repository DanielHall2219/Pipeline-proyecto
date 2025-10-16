<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_la_app_responde_sin_error(): void
    {
        $response = $this->get('/');
        // Acepta 200 (OK) o 302 (redirect a login), y cualquier status < 500 (sin error de servidor)
        $this->assertLessThan(500, $response->getStatusCode());
    }
}
