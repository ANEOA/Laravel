# Используем официальный образ PHP 8.1 с поддержкой FPM
FROM php:8.2-fpm

# Устанавливаем необходимые зависимости для системы
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    pkg-config

# Устанавливаем расширения PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd pdo pdo_mysql mbstring exif pcntl bcmath

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем проект в контейнер
COPY . /var/www

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Настраиваем права на файлы
RUN chown -R www-data:www-data /var/www

# Настраиваем права на файлы
RUN mkdir -p /var/www/bootstrap/cache && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Устанавливаем права на директории кеша
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Устанавливаем права для запуска
USER www-data
