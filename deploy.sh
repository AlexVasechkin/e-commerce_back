#!/bin/sh

php ./composer.phar config --no-plugins allow-plugins.php-http/discovery true &&
php ./composer.phar install &&
php bin/console doctrine:migrations:migrate

cd /var/www/html
apt update
apt install -y npm

npm install
npm run build
