FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# 🔥 ELIMINAR TODOS LOS MPM
RUN rm -rf /etc/apache2/mods-enabled/mpm_*

# 🔥 ACTIVAR SOLO UNO
RUN a2enmod mpm_prefork
RUN a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

# Laravel public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80