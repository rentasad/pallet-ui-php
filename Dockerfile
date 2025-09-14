# Use the official PHP Apache image
FROM php:8.3-apache

# Install system dependencies and Microsoft ODBC Driver + SQLSRV extensions
RUN apt-get update && apt-get install -y     gnupg2 apt-transport-https unixodbc-dev curl libxml2-dev     && curl -sSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /usr/share/keyrings/microsoft.gpg     && echo "deb [signed-by=/usr/share/keyrings/microsoft.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/microsoft.list     && apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql18 mssql-tools18     && pecl install sqlsrv pdo_sqlsrv     && docker-php-ext-enable sqlsrv pdo_sqlsrv   && rm -rf /var/lib/apt/lists/*
# (steht schon) a2enmod rewrite
RUN a2enmod rewrite \
 && printf '%s\n' \
    '<Directory /var/www/public>' \
    '    AllowOverride All' \
    '    Require all granted' \
    '</Directory>' \
    > /etc/apache2/conf-available/z-override.conf \
 && a2enconf z-override

# Configure Apache document roots
RUN mkdir -p /var/www/public
ENV APACHE_DOCUMENT_ROOT=/var/www/public

# Update the default Apache site
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf     && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy code (mounted in docker-compose during dev; this COPY supports build-only)
COPY public /var/www/public
COPY src /var/www/html

# Set timezone
ENV TZ=Europe/Berlin