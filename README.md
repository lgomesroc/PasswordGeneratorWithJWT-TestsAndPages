# Gerador de senhas salvas
Este projeto é uma API simples para gerenciar senhas, desenvolvida com **Flight PHP** e **SQLite**. Ele permite gerar, listar, atualizar e deletar senhas de forma segura e eficiente.

## Funcionalidades
- **Gerar senha:** Gera uma senha aleatória de 16 caracteres e a salva no banco de dados.
- **Listar senhas:** Retorna todas as senhas salvas no banco de dados.
- **Atualizar senha:** Atualiza uma senha existente com base no ID.
- **Deletar senha:** Remove uma senha do banco de dados com base no ID.
- **Autenticação JWT:** Protege a API para que apenas usuários autorizados possam acessar e gerenciar as senhas.
- **Registro de usuários:** Criação de contas com validação de dados.

## Tecnologias utilizadas
- **Backend:** `Flight PHP`
- **Banco de Dados:** `SQLite`
- **Containerização:** `Docker`
- **Gerenciamento de Dependências:** `Composer`
- **Autenticação:** `JWT` (JSON Web Tokens)

## Endpoints
```
#Método	 Endpoint	         Descrição
3GET	 /	                 Verificar se a API está rodando.
POST	 /register	         Registrar um novo usuário.
POST	 /login  	         Realizar login e obter o token JWT.
POST	 /generate	         Gerar uma nova senha (autenticado).
GET	 /passwords	         Listar todas as senhas (autenticado).
PUT	 /passwords/{id}         Atualizar uma senha específica (autenticado).
DELETE	 /passwords/{id}         Deletar uma senha específica (autenticado).
```

## Estrutura das pastas
```
.
└── PasswordGenerator
    ├── Dockerfile
    ├── LICENSE
    ├── README.md
    ├── app
    │   ├── index.php
    │   └── init_db.php
    ├── composer.json
    └── docker-compose.yml
```

## Como usar
### Pré-requisitos
- **Docker** e **Docker Compose** instalados.
- **Git** (opcional, para clonar o repositório).

### Passos para executar o projeto
- Clone o repositório:
```
git clone https://github.com/lgomesroc/PasswordGenerator.git
cd PasswordGenerator
```
- Suba o container:
```
docker-compose up --build
```
Isso irá:

- Construir a imagem do Docker.
- Instalar as dependências do Composer.
- Iniciar o servidor PHP na porta 8000.

### Inicializar o contêiner
Para inicializar o contêiner, digite o comando abaixo:
```
docker-compose down
docker-compose up -d
```

### Entrar no contêiner
Para entrar no contêiner, digite o seguinte comando:
```
docker exec -it flight_senhas_app bash
```

### Acessar o banco de dados
Para acessar o SQLite é só digitar o comando abaixo dentro do contêiner.
```
sqlite3 app/database.db
```

### Criar tabela passwords mo banco de dados
Digite a query abaixo dentro do SQLite:
```
CREATE TABLE IF NOT EXISTS passwords (
id INTEGER PRIMARY KEY AUTOINCREMENT,
password TEXT NOT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Criar tabela users no banco de dados
Digitar a seguinte query dentro do SQLite:
```
CREATE TABLE IF NOT EXISTS users (
id INTEGER PRIMARY KEY AUTOINCREMENT,
username TEXT NOT NULL UNIQUE,
password TEXT NOT NULL
);
```

###  Executar o script de inicialização do banco de dados
Digitar o seguinte comando dentro do contêiner:
```
php app/init_db.php
```

### Acesse a API:

A API estará disponível em http://localhost:8000.

### Rotas da API
#### 1. Rota Principal
   - **Método:** `GET`
   - **URL:** `/`
   - **Descrição:** Retorna uma mensagem indicando que a API está rodando.
   ```
   curl http://localhost:8000/
   ```
   - **Resposta:**
   ```
   {"message":"Gerador de senhas API está rodando"}
   ```

#### 2. Registrar Usuário
   - **Método:** `POST`
   - **URL:** `/register`
   - **Descrição:** Registra um novo usuário no sistema.
   ```
   curl -X POST -H "Content-Type: application/json" -d '{"username":"luciano","password":"senha123"}' http://localhost:8000/register
   ```
   - **Resposta:**
   ```
   {"message":"Usuário registrado com sucesso!"}
   ```

### 3. Fazer Login
   - **Método:** `POST`
   - **URL:** `/login`
   - **Descrição:** Autentica o usuário e retorna um token JWT.
   ```
   curl -X POST -H "Content-Type: application/json" -d '{"username":"luciano","password":"senha123"}' http://localhost:8000/login
   ```
   - **Resposta:**
   ```
   {"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."}
   ```

### 4. Gerar Senha
   - **Método:** `POST`
   - **URL:** `/generate`
   - **Descrição:** Gera uma senha aleatória de 16 caracteres e a salva no banco de dados.
   ```
   curl -X POST -H "Authorization: Bearer <seu_token>" http://localhost:8000/generate
   ```
   - **Resposta:**
   ```
   {"message":"Senha gerada com sucesso!","password":"a1b2c3d4e5f6g7h8"}
   ```

### 5. Listar Senhas
   - **Método:** `GET`
   - **URL:** `/passwords`
   - **Descrição:** Retorna todas as senhas salvas no banco de dados.
   ```
   curl -H "Authorization: Bearer <seu_token>" http://localhost:8000/passwords
   ```
   - **Resposta:**
   ```
   [
   {"id":1,"password":"a1b2c3d4e5f6g7h8","created_at":"2023-10-10 12:34:56"},
   {"id":2,"password":"novaSenha123","created_at":"2023-10-10 12:35:56"}
   ]
   ```

### 6.Atualizar Senha
   - **Método:** `PUT`
   - **URL:** `/passwords/:id`
   - **Descrição:** Atualiza uma senha existente com base no ID.
   ```
   curl -X PUT -H "Authorization: Bearer <seu_token>" -H "Content-Type: application/json" -d '{"password":"novaSenha123"}' http://localhost:8000/passwords/1
   ```
   - **Resposta:**
   ```
   {"message":"Senha atualizada com sucesso!","password":"novaSenha123"}
   ```

### 7. Deletar Senha
   - **Método:** `DELETE`
   - **URL:** `/passwords/:id`
   - **Descrição:** Remove uma senha do banco de dados com base no ID.
   ```
   curl -X DELETE -H "Authorization: Bearer <seu_token>" http://localhost:8000/passwords/1
   ```
   - **Resposta:**
   ```
   {"message":"Senha removida com sucesso!"}
   ```

## Histórico de Atualizações
   - 22/03/2025: Criação do projeto **PasswordGenerator** com funcionalidades básicas de geração, listagem, atualização e exclusão de senhas.
   - 23/03/2025: Adicionada a funcionalidade de **autenticação JWT** para proteger a API e garantir que apenas usuários autorizados possam acessar e gerenciar as senhas.
   - 24/03/2025: Adicionada funcionalidade de validação e tratamento de erros.

## Como Contribuir
   - Contribuições são bem-vindas! Siga os passos abaixo para contribuir com o projeto:

## Faça um fork do repositório.
   - Crie uma branch para sua feature ou correção:
   ```
   git checkout -b minha-feature
   ```
   - Faça commit das suas alterações:
   ```
   git commit -m "Adiciona nova funcionalidade"
   ```
   - Envie as alterações para o repositório remoto:
   ```
   git push origin minha-feature
   ```
   - Abra um Pull Request no repositório original.

## Licença
Este projeto está licenciado sob a `MIT License`. Veja o arquivo `LICENSE` para mais detalhes.

## Contato
Se tiver dúvidas ou sugestões, entre em contato:

   - **Nome:** `Luciano Rocha`
   - **E-mail:** `lgomesroc2012@gmail.com`
   - **GitHub:** `lgomesroc`

## Agradecimentos
   - À comunidade Flight PHP por fornecer um framework simples e eficiente.
   - Aos mantenedores do Docker e Composer por facilitar o desenvolvimento e a implantação de aplicações.
