# Используем официальный образ PHP с поддержкой Apache
FROM php:8.1-apache

# Установка необходимых расширений PHP и инструментов
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копирование файлов проекта в контейнер
COPY . /var/www/html

# Установка зависимостей проекта
WORKDIR /var/www/html
RUN composer install

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 755 /var/www/html

# Включение модуля переписывания Apache и настройка хоста
RUN a2enmod rewrite

# Копирование пользовательского файла конфигурации Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Установка рабочей директории
WORKDIR /var/www/html

# Экспонирование порта 80 для доступа к приложению
EXPOSE 80
