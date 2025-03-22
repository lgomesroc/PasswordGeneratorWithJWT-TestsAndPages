# Gerador de Senhas Salvas
Este projeto é uma API simples para gerenciar senhas, desenvolvida com Flight PHP e SQLite. Ele permite gerar, listar, atualizar e deletar senhas de forma segura e eficiente.

## Funcionalidades
- Gerar senha: Gera uma senha aleatória de 16 caracteres e a salva no banco de dados.
- Listar senhas: Retorna todas as senhas salvas no banco de dados.
- Atualizar senha: Atualiza uma senha existente com base no ID.
- Deletar senha: Remove uma senha do banco de dados com base no ID.

## Tecnologias Utilizadas
- Backend: `Flight PHP`
- Banco de Dados: `SQLite`
- Containerização: `Docker`
- Gerenciamento de Dependências: `Composer`

## Como Usar
### Pré-requisitos
- Docker e Docker Compose instalados.
- Git (opcional, para clonar o repositório).

### Passos para Executar o Projeto
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

#### 2. Gerar Senha
   - **Método:** `POST`
   - **URL:** `/generate`
   - **Descrição:** Gera uma senha aleatória de 16 caracteres e a salva no banco de dados.
   ```
   curl -X POST http://localhost:8000/generate
   ```
   - **Resposta:**
   ```
   {"message":"Senha gerada com sucesso!","password":"a1b2c3d4e5f6g7h8"}
   ```

#### 3. Listar Senhas
   - **Método:** `GET`
   - **URL:** `/passwords`
   - **Descrição:** Retorna todas as senhas salvas no banco de dados.
   ```
   curl http://localhost:8000/passwords
   ```
   - **Resposta:**
   ```
   [
   {"id":1,"password":"a1b2c3d4e5f6g7h8","created_at":"2023-10-10 12:34:56"},
   {"id":2,"password":"novaSenha123","created_at":"2023-10-10 12:35:56"}
   ]
   ```

#### 4. Atualizar Senha
   - **Método:** `PUT`
   - **URL:** `/passwords/:id`
   - **Descrição:** Atualiza uma senha existente com base no ID.
   ```
   curl -X PUT -H "Content-Type: application/json" -d '{"password":"novaSenha123"}' http://localhost:8000/passwords/1
   ```
   - **Resposta:**
   ```
   {"message":"Senha atualizada com sucesso!","password":"novaSenha123"}
   ```

#### 5. Deletar Senha
   - **Método:** `DELETE`
   - **URL:** `/passwords/:id`
   - **Descrição:** Remove uma senha do banco de dados com base no ID.
   ```
   curl -X DELETE http://localhost:8000/passwords/1
   ```
   - **Resposta:**
   ```
   {"message":"Senha removida com sucesso!"}
   ```

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
- Este projeto está licenciado sob a `MIT License`. Veja o arquivo `LICENSE` para mais detalhes.

## Contato
- Se tiver dúvidas ou sugestões, entre em contato:

- **Nome:** `Luciano Rocha`
- **E-mail:** `lgomesroc2012@gmail.com`
- **GitHub:** `lgomesroc`

## Agradecimentos
- À comunidade Flight PHP por fornecer um framework simples e eficiente.
- Aos mantenedores do Docker e Composer por facilitar o desenvolvimento e a implantação de aplicações.