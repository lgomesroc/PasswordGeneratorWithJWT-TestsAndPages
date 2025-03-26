<?php

// Caminho absoluto para o banco de dados
$dbPath = __DIR__ . '/database.db';
echo "Caminho do banco de dados: $dbPath\n";

// Conectar ao banco de dados SQLite (ou criar se não existir)
$db = new SQLite3($dbPath);

if (!$db) {
    die("Erro ao conectar ao banco de dados: " . $db->lastErrorMsg() . "\n");
}

echo "Conexão com o banco de dados estabelecida.\n";

// Criar a tabela 'passwords' se ela não existir
$query = "CREATE TABLE IF NOT EXISTS passwords (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($db->exec($query)) {
    echo "Tabela 'passwords' criada com sucesso!\n";
} else {
    echo "Erro ao criar a tabela: " . $db->lastErrorMsg() . "\n";
}

// Verificar se a tabela 'passwords' foi criada
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='passwords'");
if ($result->fetchArray()) {
    echo "A tabela 'passwords' existe no banco de dados.\n";
} else {
    echo "A tabela 'passwords' NÃO foi criada.\n";
}

// Criar a tabela 'users' se ela não existir
$query = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($db->exec($query)) {
    echo "Tabela 'users' criada com sucesso!\n";
} else {
    echo "Erro ao criar a tabela: " . $db->lastErrorMsg() . "\n";
}

// Verificar se a tabela 'users' foi criada
$result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
if ($result->fetchArray()) {
    echo "A tabela 'users' existe no banco de dados.\n";
} else {
    echo "A tabela 'users' NÃO foi criada.\n";
}

$db->close();