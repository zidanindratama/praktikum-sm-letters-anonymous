FROM composer:2 AS vendor

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install intl zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY . .

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev \
        libonig-dev \
        unzip \
        libzip-dev \
    && docker-php-ext-install intl mbstring mysqli \
    && a2enmod rewrite headers \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/codeigniter.conf \
    && a2enconf codeigniter \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html

RUN mkdir -p writable/cache writable/debugbar writable/logs writable/session writable/uploads public/uploads \
    && chown -R www-data:www-data writable public/uploads \
    && chmod -R 775 writable public/uploads

EXPOSE 80

CMD ["apache2-foreground"]
