<?php

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$db = new SQLite3('database.db');

// Chave secreta para assinar os tokens JWT
$jwtKey = "sua_chave_secreta"; // Substitua por uma chave segura

// Middleware de autenticação
Flight::before('start', function() use ($jwtKey) {
    $publicRoutes = ['/register', '/login']; // Rotas públicas
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

// Rota principal
Flight::route('GET /', function(){
    echo json_encode(["message" => "Gerador de senhas API está rodando"]);
});

// Rota para registrar um novo usuário
Flight::route('POST /register', function() use ($db) {
    $data = Flight::request()->data->getData();
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    // Verificar se o usuário já existe
    $query = $db->prepare("SELECT id FROM users WHERE username = :username");
    $query->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $query->execute();

    if ($result->fetchArray()) {
        Flight::json(["message" => "Usuário já existe."], 400);
        return;
    }

    // Inserir novo usuário
    $query = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $query->bindValue(':username', $username, SQLITE3_TEXT);
    $query->bindValue(':password', $password, SQLITE3_TEXT);
    $query->execute();

    Flight::json(["message" => "Usuário registrado com sucesso!"]);
});

// Rota para autenticar um usuário e gerar um token JWT
Flight::route('POST /login', function() use ($db, $jwtKey) {
    $data = Flight::request()->data->getData();
    $username = $data['username'];
    $password = $data['password'];

    // Buscar usuário no banco de dados
    $query = $db->prepare("SELECT id, password FROM users WHERE username = :username");
    $query->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $query->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        Flight::json(["message" => "Usuário ou senha inválidos."], 401);
        return;
    }

    // Gerar token JWT
    $payload = [
        "user_id" => $user['id'],
        "username" => $username,
        "exp" => time() + 3600 // Token expira em 1 hora
    ];
    $jwt = JWT::encode($payload, $jwtKey, 'HS256');

    Flight::json(["token" => $jwt]);
});

// Rota para gerar uma nova senha
Flight::route('POST /generate', function() use ($db) {
    $password = bin2hex(random_bytes(8)); // Gera uma senha aleatória de 16 caracteres
    $query = $db->prepare("INSERT INTO passwords (password) VALUES (:password)");
    $query->bindValue(':password', $password, SQLITE3_TEXT);
    $query->execute();

    echo json_encode(["message" => "Senha gerada com sucesso!", "password" => $password]);
});

// Rota para listar todas as senhas
Flight::route('GET /passwords', function() use ($db) {
    $result = $db->query("SELECT * FROM passwords");
    $passwords = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $passwords[] = $row;
    }
    echo json_encode($passwords);
});

// Rota para atualizar uma senha
Flight::route('PUT /passwords/@id', function($id) use ($db) {
    $data = Flight::request()->data->getData();
    $newPassword = $data['password'];

    // Verifica se o ID existe
    $query = $db->prepare("SELECT id FROM passwords WHERE id = :id");
    $query->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $query->execute();

    if ($result->fetchArray()) {
        // Atualiza a senha
        $query = $db->prepare("UPDATE passwords SET password = :password WHERE id = :id");
        $query->bindValue(':password', $newPassword, SQLITE3_TEXT);
        $query->bindValue(':id', $id, SQLITE3_INTEGER);
        $query->execute();

        echo json_encode(["message" => "Senha atualizada com sucesso!", "password" => $newPassword]);
    } else {
        Flight::json(["message" => "Senha não encontrada."], 404);
    }
});

// Rota para deletar uma senha
Flight::route('DELETE /passwords/@id', function($id) use ($db) {
    $query = $db->prepare("DELETE FROM passwords WHERE id = :id");
    $query->bindValue(':id', $id, SQLITE3_INTEGER);
    $query->execute();

    echo json_encode(["message" => "Senha removida com sucesso!"]);
});

Flight::start();