FROM webdevops/php:7.4-alpine

WORKDIR /app

COPY ./e-commerce_api/composer.json ./composer.json
COPY ./e-commerce_api/composer.phar ./composer.phar

RUN php composer.phar config --no-plugins allow-plugins.php-http/discovery true && \
    composer install

COPY ./e-commerce_api .
