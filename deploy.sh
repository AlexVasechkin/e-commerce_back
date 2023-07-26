#!/bin/sh

php ./composer.phar config --no-plugins allow-plugins.php-http/discovery true &&
php ./composer.phar install &&
php bin/console doctrine:migrations:migrate

cd /var/www/html

curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt-get install -y nodejs

npm install
npm run build
