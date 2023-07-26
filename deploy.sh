#!/bin/sh

php ./composer.phar config --no-plugins allow-plugins.php-http/discovery true &&
php ./composer.phar install &&
php bin/console doctrine:migrations:migrate

apt update
apt install -y nodejs

cd /var/www/html && npm run build
