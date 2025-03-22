<?php

require '../vendor/autoload.php';

$db = new SQLite3('database.db');

Flight::route('GET /', function(){
    echo json_encode(["message" => "Gerador de senhas API está rodando"]);
});

Flight::route('POST /generate', function() use ($db) {
    $password = bin2hex(random_bytes(8)); // Gera uma senha aleatória de 16 caracteres
    $query = $db->prepare("INSERT INTO passwords (password) VALUES (:password)");
    $query->bindValue(':password', $password, SQLITE3_TEXT);
    $query->execute();

    echo json_encode(["message" => "Senha gerada com sucesso!", "password" => $password]);
});

Flight::route('GET /passwords', function() use ($db) {
    $result = $db->query("SELECT * FROM passwords");
    $passwords = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $passwords[] = $row;
    }
    echo json_encode($passwords);
});

Flight::route('PUT /passwords/@id', function($id) use ($db) {
    // Acessa os dados brutos da requisição
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

Flight::route('DELETE /passwords/@id', function($id) use ($db) {
    $query = $db->prepare("DELETE FROM passwords WHERE id = :id");
    $query->bindValue(':id', $id, SQLITE3_INTEGER);
    $query->execute();

    echo json_encode(["message" => "Senha removida com sucesso!"]);
});

Flight::start();