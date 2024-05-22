# Menggunakan gambar resmi PHP dengan Apache
FROM php:7.4-apache

# Menginstal ekstensi PHP yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli pdo pdo_mysql zip

# Mengaktifkan modul Apache rewrite
RUN a2enmod rewrite

# Mengatur working directory
WORKDIR /var/www/html

# Menyalin file aplikasi ke dalam container
COPY . /var/www/html

# Memberikan hak akses yang tepat untuk file dan direktori
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Menyalin file konfigurasi virtual host Apache
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Menjalankan perintah Composer untuk menginstal dependensi aplikasi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Mengekspos port 80 untuk Apache
EXPOSE 80

# Perintah untuk menjalankan Apache
CMD ["apache2-foreground"]