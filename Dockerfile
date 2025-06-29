# Usamos una imagen oficial de PHP
FROM php:8.1-apache

# Instalamos dependencias necesarias de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    libzip-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libxml2-dev \
    git \
    unzip \
    curl \
    build-essential \
    vim \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl intl bcmath opcache

# Instalamos la extensión de MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalamos Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalamos Node.js y npm
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Verificamos la instalación de Node.js y npm
RUN node -v && npm -v

# Cambiamos a usuario root
ENV COMPOSER_ALLOW_SUPERUSER=1
USER root

# Copiamos los archivos al contenedor
COPY . /var/www/html
COPY .env /var/www/html/.env

# Copiamos el archivo de configuración personalizado de Apache
COPY dockerConfigs/000-default-dev.conf /etc/apache2/sites-available/000-default.conf

# Habilitamos el sitio de Apache
RUN a2ensite 000-default.conf

# Habilitamos el módulo rewrite de Apache
RUN a2enmod rewrite

# Cambiamos permisos
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 /var/www/html

# Instalamos las dependencias de npm (frontend)
WORKDIR /var/www/html
RUN npm install

# Limpiamos la caché de npm para reducir el tamaño de la imagen
RUN npm cache clean --force

# Construimos los activos de Vite (frontend) para producción
RUN npm run build

# Ejecutamos composer install para el backend (Laravel)
RUN composer install

# Habilitamos mod_rewrite y reiniciamos Apache
RUN a2enmod rewrite && service apache2 restart

# Ejecutamos otros comandos necesarios de Laravel
RUN php artisan config:cache
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan optimize:clear

# Exponemos el puerto 80 para la aplicación web
EXPOSE 80

# Exponemos el puerto de Vite para desarrollo
EXPOSE 5173

# Iniciamos Apache y el servidor de Vite en modo desarrollo
CMD ["sh", "-c", "npm run dev & apache2-foreground"]
