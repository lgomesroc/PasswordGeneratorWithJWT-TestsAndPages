# Gerador de senhas salvas
Este projeto é uma API simples para gerenciar senhas, desenvolvida com **Flight PHP** e **SQLite**. Ele permite 
gerar, listar, atualizar e deletar senhas de forma segura e eficiente.

## Funcionalidades

### **Backend**
- **Gerar senha:** Gera uma senha aleatória de 16 caracteres e a salva no banco de dados.
- **Listar senhas:** Retorna todas as senhas salvas no banco de dados.
- **Atualizar senha:** Atualiza uma senha existente com base no ID.
- **Deletar senha:** Remove uma senha do banco de dados com base no ID.
- **Autenticação JWT:** Protege a API para que apenas usuários autorizados possam acessar e gerenciar as senhas.
- **Registro de usuários:** Permite a criação de contas com validação de dados.
- **Tratamento de erros:** Valida os dados enviados, retornando mensagens claras em caso de inconsistências.
- **Teste automatizado:** Validação de rotas, autenticação e tratamento de dados usando PHPUnit.
- **Documentação da API:** Todas as rotas foram documentadas usando Swagger, permitindo gerar documentação interativa 
e facilitar o consumo da API por desenvolvedores.
---

### **Frontend**
- **Login de usuários:** Permite que usuários façam login com `username` e `password`, recebendo um token JWT.
- **Exibir senhas:** Lista as senhas do usuário logado, integrando com a API do backend.
- **Gerar nova senha:** Inclui um botão para solicitar uma nova senha, utilizando o token JWT para autenticação.
- **Logout:** Permite que o usuário encerre a sessão removendo o token JWT do armazenamento local.
- **Cadastro de usuários:** Adicionada funcionalidade para criar novos usuários, integrando com a API do backend.
- **Interface intuitiva:** Navegação facilitada com React Router DOM, estilização limpa com Bootstrap, e 
requisições eficientes usando Axios.


---

## Tecnologias utilizadas

### **Backend**
- **Framework:** `Flight PHP`
- **Banco de dados:** `SQLite`
- **Containerização:** `Docker`
- **Gerenciamento de dependências:** `Composer`
- **Autenticação:** `JWT` (JSON Web Tokens)
- **Validação dos dados:** `Respect\Validation`
- **Testes automatizados:** `PHPUnit`
- **Documentação da API:** `Swagger` (via `zircote/swagger-php`)

### **Frontend**
- **Framework:** `React`
- **Gerenciamento de rotas:** `React Router DOM`
- **Requisições HTTP:** `Axios`
- **Estilização:** `Bootstrap`
- **Containerização:** `Docker`


---

## Backend
### Endpoints
```
#Método	 Endpoint	         Descrição
GET	 /	                 Verificar se a API está rodando.
POST	 /register	         Registrar um novo usuário.
POST	 /login  	         Realizar login e obter o token JWT.
POST	 /generate	         Gerar uma nova senha (autenticado).
GET	 /passwords	         Listar todas as senhas (autenticado).
PUT	 /passwords/{id}         Atualizar uma senha específica (autenticado).
DELETE	 /passwords/{id}         Deletar uma senha específica (autenticado).
```

---

### Como usar
#### Pré-requisitos
- **Docker** e **Docker Compose** instalados.
- **Git** (opcional, para clonar o repositório).

#### Passos para executar o projeto
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

#### Inicializar o contêiner
Para inicializar o contêiner, digite o comando abaixo:
```
docker-compose down
docker-compose up -d
```

#### Entrar no contêiner
Para entrar no contêiner, digite o seguinte comando:
```
docker exec -it flight_senhas_app bash
```

---

#### Acessar o banco de dados
Para acessar o SQLite é só digitar o comando abaixo dentro do contêiner.
```
sqlite3 app/database.db
```

#### Criar tabela passwords mo banco de dados
Digite a query abaixo dentro do SQLite:
```
CREATE TABLE IF NOT EXISTS passwords (
id INTEGER PRIMARY KEY AUTOINCREMENT,
password TEXT NOT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

#### Criar tabela users no banco de dados
Digitar a seguinte query dentro do SQLite:
```
CREATE TABLE IF NOT EXISTS users (
id INTEGER PRIMARY KEY AUTOINCREMENT,
username TEXT NOT NULL UNIQUE,
password TEXT NOT NULL
);
```

####  Executar o script de inicialização do banco de dados
Digitar o seguinte comando dentro do contêiner:
```
php app/init_db.php
```

---

#### Acesse a API:

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
   curl -X POST -H "Content-Type: application/json" -d '{"username":"luciano","password":"senha123"}' 
  http://localhost:8000/register
   ```
- **Resposta:**
   ```
   {"message":"Usuário registrado com sucesso!"}
   ```

#### 3. Fazer Login
- **Método:** `POST`
- **URL:** `/login`
- **Descrição:** Autentica o usuário e retorna um token JWT.
   ```
   curl -X POST -H "Content-Type: application/json" -d '{"username":"luciano","password":"senha123"}' 
  http://localhost:8000/login
   ```
- **Resposta:**
   ```
   {"token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."}
   ```

#### 4. Gerar Senha
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

#### 5. Listar Senhas
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

#### 6.Atualizar Senha
- **Método:** `PUT`
- **URL:** `/passwords/:id`
- **Descrição:** Atualiza uma senha existente com base no ID.
   ```
   curl -X PUT -H "Authorization: Bearer <seu_token>" -H "Content-Type: 
  application/json" -d '{"password":"novaSenha123"}' http://localhost:8000/passwords/1
   ```
- **Resposta:**
   ```
   {"message":"Senha atualizada com sucesso!","password":"novaSenha123"}
   ```

#### 7. Deletar Senha
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

---

### Histórico de Atualizações
- 22/03/2025: Criação do projeto **PasswordGenerator** com funcionalidades básicas de geração, listagem, atualização 
e exclusão de senhas.
- 23/03/2025: Adicionada a funcionalidade de **autenticação JWT** para proteger a API e garantir que apenas 
usuários autorizados possam acessar e gerenciar as senhas.
- 24/03/2025: Adicionada funcionalidade de validação e tratamento de erros.
- 26/03/2025: Implementados **testes automatizados** com PHPUnit para garantir a qualidade e a confiabilidade 
do código, incluindo testes unitários e de integração.
- 27/03/2025:
  - Configurado o **frontend em React** para fornecer uma interface amigável ao usuário, com funcionalidades 
  de login, exibição de senhas e geração de novas senhas.
  - Integrado o **React Router DOM**, **Axios** e **Bootstrap** ao frontend para rotas dinâmicas, 
  requisições e estilização.
  - Implementado suporte para execução do projeto completo (frontend e backend) utilizando 
  **Docker** com `docker-compose`.

---

### Testes Automatizados

#### **Testes Unitários**
Os testes unitários verificam funcionalidades específicas e isoladas da aplicação:
- **UserValidationTest:**
  - Valida cenários relacionados à função de validação de registros de usuários, garantindo que nomes de usuário e 
  senhas estejam dentro dos padrões esperados.
- **ValidationTest:**
  - Testa outras funções de validação, como verificação de senha, para assegurar que os dados atendem aos 
  critérios definidos.

#### **Testes de Integração**
Os testes de integração verificam o funcionamento completo das rotas e sua interação com o sistema:
- **GeneratePasswordTest:**
  - Testa a rota `/generate` para verificar se ela gera uma nova senha com sucesso, utilizando um token JWT válido.
- **ProtectedRouteTest:**
  - Valida se o acesso a rotas protegidas, como `/passwords`, retorna erro quando o token JWT não está presente 
  ou é inválido.
- **RegisterRouteTest:**
  - Testa a rota `/register`, garantindo que novos usuários sejam registrados corretamente e que duplicações 
  sejam evitadas.

---

## Frontend
O frontend React será servido por padrão na **porta 3000**, mas pode ser movido para outra porta se a 3000 já 
estiver ocupada.

- Para acessar o frontend, abra [http://localhost:3001](http://localhost:3001) no seu navegador.

Se a porta **3001** também estiver ocupada, verifique os logs do contêiner `password_generator_frontend` para 
confirmar a porta correta:
```
docker logs password_generator_frontend
```

### Executando o Frontend

Para rodar o frontend do projeto localmente de forma automática, siga os passos abaixo:

1. **Acesse a pasta do frontend:**
   No terminal, navegue para o diretório onde o frontend está localizado:
```
cd frontend
```
2. **Inicie o servidor de desenvolvimento:** execute o seguinte comando para iniciar o React:

bash
npm start
3. **Confirme o redirecionamento da porta:** caso a porta padrão 3000 já esteja em uso, o React perguntará se deseja 
rodar o projeto em uma porta alternativa (por exemplo, 3001). Responda Y (sim) quando solicitado. Após isso, o 
projeto abrirá automaticamente no navegador na URL correspondente, como:

http://localhost:3001

4. **Acesse manualmente (se necessário):** Se o navegador não abrir automaticamente, você pode acessar a URL no 
terminal (normalmente exibida após o comando npm start).

---

## Como Contribuir
- Contribuições são bem-vindas! Siga os passos abaixo para contribuir com o projeto:

### Faça um fork do repositório.
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

---

## Licença
Este projeto está licenciado sob a `MIT License`. Veja o arquivo `LICENSE` para mais detalhes.

---

## Contato
Se tiver dúvidas ou sugestões, entre em contato:

- **Nome:** `Luciano Rocha`
- **E-mail:** `lgomesroc2012@gmail.com`
- **GitHub:** `lgomesroc`

---

## Agradecimentos
- À comunidade Flight PHP por fornecer um framework simples e eficiente.
- Aos mantenedores do Docker e Composer por facilitar o desenvolvimento e a implantação de aplicações.
