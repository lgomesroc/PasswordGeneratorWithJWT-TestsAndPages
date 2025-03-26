<?php

use PHPUnit\Framework\TestCase;

class UserValidationTest extends TestCase
{
    public function testValidUsername()
    {
        $username = "usuario_teste";
        $this->assertTrue(strlen($username) >= 3 && strlen($username) <= 50);
    }

    public function testInvalidUsername()
    {
        $username = "";
        $this->assertFalse(strlen($username) >= 3 && strlen($username) <= 50);
    }

    public function testValidPassword()
    {
        $password = "senha123";
        $this->assertTrue(strlen($password) >= 6);
    }

    public function testInvalidPassword()
    {
        $password = "abc";
        $this->assertFalse(strlen($password) >= 6);
    }
}
