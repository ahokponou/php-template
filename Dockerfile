FROM composer:latest AS composer
FROM php:8.4.15-apache-trixie

# Set custom UID/GID for www-data to match host user (for mounted volumes)
ENV UID=1000
ENV GID=1000

# Install dependencies
RUN apt-get update && apt-get install -y \
    openssl \
    wget \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Copy composer from composer stage
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Enable Apache modules for SSL
RUN a2enmod ssl rewrite headers

# Generate self-signed certificate for development
RUN mkdir -p /etc/apache2/certs && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/apache2/certs/apache-selfsigned.key \
    -out /etc/apache2/certs/apache-selfsigned.crt \
    -subj "/C=FR/L=Development/O=Development/CN=localhost"

RUN cat <<CONF >/etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}$1 [R=301,L]
</VirtualHost>

<VirtualHost *:443>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    SSLEngine on
    SSLCertificateFile /etc/apache2/certs/apache-selfsigned.crt
    SSLCertificateKeyFile /etc/apache2/certs/apache-selfsigned.key

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
CONF

RUN usermod -u ${UID} www-data && \
    groupmod -g ${GID} www-data && \
    chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

EXPOSE 443