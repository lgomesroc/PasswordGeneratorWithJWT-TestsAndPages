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

# Instalar pacotes via Composer (incluindo Respect/Validation e Firebase JWT)
WORKDIR /var/www/html
COPY . .
RUN composer require respect/validation firebase/php-jwt mikecao/flight && composer install

# Instalar PHPUnit como dependência de desenvolvimento
RUN composer require --dev phpunit/phpunit

# Expor a porta do servidor PHP
EXPOSE 8000

# Comando para rodar o servidor embutido do PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "app"]
