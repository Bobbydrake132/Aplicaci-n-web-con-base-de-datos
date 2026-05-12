# Usamos una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos la extensión PDO MySQL para conectarnos a Railway
RUN docker-php-ext-install pdo pdo_mysql

# Copiamos tus archivos al directorio del servidor web
COPY . /var/www/html/

# Exponemos el puerto 80
EXPOSE 80
