FROM php:8.2-apache
 
# Устанавливаем зависимости для pdo_pgsql
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
 
# Копируем файлы приложения
COPY . /var/www/html/
RUN chmod -R 755 /var/www/html/
