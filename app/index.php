<?php

require __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Respect\Validation\Validator as v;

$db = new SQLite3('database.db');

// Chave secreta para assinar os tokens JWT
$jwtKey = "sua_chave_secreta"; // Substitua por uma chave segura

// Funções de validação
function validateUserRegistration($data) {
    $usernameValidator = v::stringType()->notEmpty()->length(3, 50);
    $passwordValidator = v::stringType()->notEmpty()->length(6);

    if (!$usernameValidator->validate($data['username'])) {
        throw new Exception("O nome de usuário deve ter entre 3 e 50 caracteres.");
    }

    if (!$passwordValidator->validate($data['password'])) {
        throw new Exception("A senha deve ter pelo menos 6 caracteres.");
    }
}

function validateNewPassword($password) {
    if (empty($password) || strlen($password) < 8) {
        throw new Exception("A nova senha deve ter pelo menos 8 caracteres.");
    }
}

// Middleware de autenticação
Flight::before('start', function() use ($jwtKey) {
    $publicRoutes = ['/', '/register', '/login']; // Rotas públicas
    $requestPath = Flight::request()->url;

    if (in_array($requestPath, $publicRoutes)) {
        return true; // Ignorar autenticação para rotas públicas
    }

    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';

    if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        Flight::json(["message" => "Token de autenticação ausente ou inválido."], 401);
        return false;
    }

    $token = $matches[1];

    try {
        $decoded = JWT::decode($token, new Key($jwtKey, 'HS256'));
        Flight::set('user', $decoded); // Armazenar dados do usuário na requisição
    } catch (Exception $e) {
        Flight::json(["message" => "Token inválido ou expirado."], 401);
        return false;
    }
});

// Tratamento global de erros
Flight::map('error', function(Exception $ex) {
    header('Content-Type: application/json; charset=utf-8');
    Flight::json([
        "error" => true,
        "message" => $ex->getMessage(),
        "timestamp" => date("Y-m-d H:i:s")
    ], $ex->getCode() ?: 400);
});

// Rota principal
Flight::route('GET /', function(){
    Flight::json(["message" => "Gerador de senhas API está rodando"]);
});

// Rota para registrar um novo usuário
Flight::route('POST /register', function() use ($db) {
    try {
        $data = Flight::request()->data->getData();
        validateUserRegistration($data);

        $username = $data['username'];
        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        // Verificar se o usuário já existe
        $query = $db->prepare("SELECT id FROM users WHERE username = :username");
        $query->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $query->execute();

        if ($result->fetchArray()) {
            throw new Exception("Usuário já existe.");
        }

        // Inserir novo usuário
        $query = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $query->bindValue(':username', $username, SQLITE3_TEXT);
        $query->bindValue(':password', $password, SQLITE3_TEXT);
        $query->execute();

        Flight::json(["message" => "Usuário registrado com sucesso!"]);
    } catch (Exception $e) {
        Flight::error($e);
    }
});

// Rota para autenticar um usuário e gerar um token JWT
Flight::route('POST /login', function() use ($db, $jwtKey) {
    try {
        $data = Flight::request()->data->getData();
        $username = $data['username'];
        $password = $data['password'];

        // Buscar usuário no banco de dados
        $query = $db->prepare("SELECT id, password FROM users WHERE username = :username");
        $query->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $query->execute();
        $user = $result->fetchArray(SQLITE3_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            throw new Exception("Usuário ou senha inválidos.", 401);
        }

        // Gerar token JWT
        $payload = [
            "user_id" => $user['id'],
            "username" => $username,
            "exp" => time() + 3600 // Token expira em 1 hora
        ];
        $jwt = JWT::encode($payload, $jwtKey, 'HS256');

        Flight::json(["token" => $jwt]);
    } catch (Exception $e) {
        Flight::error($e);
    }
});

// Rota para gerar uma nova senha
Flight::route('POST /generate', function() use ($db) {
    try {
        $password = bin2hex(random_bytes(8)); // Gera uma senha aleatória de 16 caracteres
        $query = $db->prepare("INSERT INTO passwords (password) VALUES (:password)");
        $query->bindValue(':password', $password, SQLITE3_TEXT);
        $query->execute();

        Flight::json(["message" => "Senha gerada com sucesso!", "password" => $password]);
    } catch (Exception $e) {
        Flight::error($e);
    }
});

// Rota para listar todas as senhas
Flight::route('GET /passwords', function() use ($db) {
    try {
        $result = $db->query("SELECT * FROM passwords");
        $passwords = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $passwords[] = $row;
        }
        Flight::json($passwords);
    } catch (Exception $e) {
        Flight::error($e);
    }
});

// Rota para atualizar uma senha
Flight::route('PUT /passwords/@id', function($id) use ($db) {
    try {
        $data = Flight::request()->data->getData();
        validateNewPassword($data['password']);

        $query = $db->prepare("SELECT id FROM passwords WHERE id = :id");
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $query->execute();

        if ($result->fetchArray()) {
            $query = $db->prepare("UPDATE passwords SET password = :password WHERE id = :id");
            $query->bindValue(':password', $data['password'], SQLITE3_TEXT);
            $query->bindValue(':id', $id, SQLITE3_INTEGER);
            $query->execute();

            Flight::json(["message" => "Senha atualizada com sucesso!", "password" => $data['password']]);
        } else {
            throw new Exception("Senha não encontrada.", 404);
        }
    } catch (Exception $e) {
        Flight::error($e);
    }
});

// Rota para deletar uma senha
Flight::route('DELETE /passwords/@id', function($id) use ($db) {
    try {
        $query = $db->prepare("DELETE FROM passwords WHERE id = :id");
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        $query->execute();

        Flight::json(["message" => "Senha removida com sucesso!"]);
    } catch (Exception $e) {
        Flight::error($e);
    }
});

Flight::start();
