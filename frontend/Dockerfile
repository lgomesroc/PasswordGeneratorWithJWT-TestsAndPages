# Usar a imagem oficial do Node.js
FROM node:18-alpine

# Criar e definir o diretório de trabalho
WORKDIR /app

# Copiar os arquivos de dependências
COPY package.json ./
COPY package-lock.json ./

# Instalar dependências básicas e pacotes adicionais
RUN npm install && \
    npm install react-router-dom axios bootstrap

# Copiar o restante dos arquivos da aplicação
COPY . .

# Build da aplicação React
RUN npm run build

# Expor a aplicação na porta 3000
EXPOSE 3000

# Iniciar um servidor para servir o frontend
CMD ["npx", "serve", "-s", "build", "-l", "3000"]
