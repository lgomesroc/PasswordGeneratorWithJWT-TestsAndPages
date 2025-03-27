<?php

use PHPUnit\Framework\TestCase;

require_once './app/index.php'; // Inclua o arquivo principal do projeto para acessar as funções.

class ValidationTest extends TestCase
{
    public function testValidUsername()
    {
        $data = ['username' => 'usuario_teste', 'password' => 'senha123'];
        $this->expectNotToPerformAssertions();
        validateUserRegistration($data);
    }

    public function testInvalidUsername()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("O nome de usuário deve ter entre 3 e 50 caracteres.");

        $data = ['username' => '', 'password' => 'senha123'];
        validateUserRegistration($data);
    }

    public function testValidPassword()
    {
        $data = ['username' => 'usuario_teste', 'password' => 'senha123'];
        $this->expectNotToPerformAssertions();
        validateUserRegistration($data);
    }

    public function testInvalidPassword()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("A senha deve ter pelo menos 6 caracteres.");

        $data = ['username' => 'usuario_teste', 'password' => 'abc'];
        validateUserRegistration($data);
    }
}
