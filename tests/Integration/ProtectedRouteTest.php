<?php

use PHPUnit\Framework\TestCase;

class ProtectedRouteTest extends TestCase
{
    private $baseUrl = 'http://localhost:8000';

    public function testAccessWithoutToken()
    {
        $response = $this->makeGetRequest('/passwords');

        $this->assertEquals(401, $response['status']);
        $this->assertStringContainsString("Token de autenticação ausente ou inválido.", $response['body']);
    }

    private function makeGetRequest($route)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $route);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            "status" => $status,
            "body" => $body
        ];
    }
}
