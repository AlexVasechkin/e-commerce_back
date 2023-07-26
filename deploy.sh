#!/bin/sh

php ./composer.phar config --no-plugins allow-plugins.php-http/discovery true &&
php ./composer.phar install &&
php bin/console doctrine:migrations:migrate

curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

apt update
apt install -y yarn

yarn build
