<?php

use PHPUnit\Framework\TestCase;

class GeneratePasswordTest extends TestCase
{
    private $baseUrl = 'http://localhost:8000';
    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo1LCJ1c2VybmFtZSI6InVzdWFyaW9fbm92byIsImV4cCI6MTc0MzAxMjY4NH0.rPEhJL6WdslN406pnHEX9g2d7BXo8_9Jc0DEMOfjsIs';

    public function testGeneratePassword()
    {
        $response = $this->makePostRequest('/generate');

        $this->assertEquals(200, $response['status']);
        $this->assertStringContainsString("Senha gerada com sucesso!", $response['body']);
    }

    private function makePostRequest($route)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $route);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->token,
            "Content-Type: application/json"
        ]);

        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            "status" => $status,
            "body" => $body
        ];
    }
}
