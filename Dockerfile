FROM php:8.4-cli AS base

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

FROM base AS app

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public", "public/index.php"]

FROM base AS test

RUN composer install --optimize-autoloader

CMD ["./vendor/bin/phpunit", "--testdox"]
