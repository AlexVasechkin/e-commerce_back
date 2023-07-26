#!/bin/sh

php ./composer.phar config --no-plugins allow-plugins.php-http/discovery true &&
php ./composer.phar install &&
php bin/console doctrine:migrations:migrate

cd /var/www/html

curl https://raw.githubusercontent.com/creationix/nvm/master/install.sh | bash
source ~/.bashrc

nvm install --lts
nvm use --lts

npm install
npm run build
