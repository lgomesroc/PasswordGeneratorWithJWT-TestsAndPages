# Usar a imagem base do PHP
FROM php:8.3-cli

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    sqlite3 \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões necessárias para SQLite
RUN docker-php-ext-install pdo pdo_sqlite

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir o diretório de trabalho
WORKDIR /var/www/backend

# Copiar os arquivos do backend para o contêiner
COPY . .

# Instalar dependências incluindo Swagger
RUN composer install && composer require zircote/swagger-php

# Expor a porta do servidor PHP
EXPOSE 8000

# Comando para rodar o servidor embutido do PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "app"]
