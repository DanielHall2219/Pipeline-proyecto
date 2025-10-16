<?php

test('la app responde correctamente', function () {
    $response = $this->get('/');
    expect($response->getStatusCode())->toBeGreaterThanOrEqual(200);
});
