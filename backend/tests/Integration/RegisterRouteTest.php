<?php

use PHPUnit\Framework\TestCase;

class RegisterRouteTest extends TestCase
{
    private $baseUrl = 'http://localhost:8000';

    public function testRegisterUser()
    {
        $data = [
            "username" => "usuario_teste",
            "password" => "senha123"
        ];

        $response = $this->makePostRequest('/register', $data);

        $this->assertEquals(200, $response['status']);
        $this->assertStringContainsString("Usuário registrado com sucesso!", $response['body']);
    }

    public function testDuplicateRegisterUser()
    {
        $data = [
            "username" => "usuario_teste",
            "password" => "senha123"
        ];

        $this->makePostRequest('/register', $data); // Primeiro registro
        $response = $this->makePostRequest('/register', $data); // Tentativa de duplicação

        $this->assertEquals(400, $response['status']);
        $this->assertStringContainsString("Usuário já existe.", $response['body']);
    }

    private function makePostRequest($route, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $route);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
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
