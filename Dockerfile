# Menggunakan gambar resmi PHP 8.3 dengan Apache
FROM php:8.3-apache

# Menginstal ekstensi PHP yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli pdo pdo_mysql zip intl

# Mengaktifkan modul Apache rewrite
RUN a2enmod rewrite

# Mengatur working directory
WORKDIR /var/www/html

# Menyalin file aplikasi ke dalam container
COPY . /var/www/html

# Menambahkan phpinfo.php untuk pengecekan versi PHP
RUN echo '<?php phpinfo(); ?>' > /var/www/html/public/phpinfo.php

# Memberikan hak akses yang tepat untuk file dan direktori
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Menyalin file konfigurasi virtual host Apache
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Menyalin dan menjalankan perintah Composer untuk menginstal dependensi aplikasi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Jalankan composer install dan tangkap error
RUN composer install || { echo "Composer install failed"; exit 1; }

# Buat folder uploads dan set hak aksesnya
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 755 /var/www/html/public/uploads

# Menandai /var/www/html/public/uploads sebagai volume
VOLUME /var/www/html/public/uploads

# Mengekspos port 80 untuk Apache
EXPOSE 80

# Perintah untuk menjalankan Apache
CMD ["apache2-foreground"]
