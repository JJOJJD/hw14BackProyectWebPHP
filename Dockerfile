FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN a2enmod rewrite

COPY . /var/www/html/

# Install PHP dependencies
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Ajustar Apache para que escuche en el puerto asignado por Render
ENV PORT=80
RUN sed -s -i -e "s/80/\${PORT}/" /etc/apache2/ports.conf
RUN sed -s -i -e "s/80/\${PORT}/" /etc/apache2/sites-available/000-default.conf

EXPOSE ${PORT}
