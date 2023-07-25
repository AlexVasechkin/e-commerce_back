#!/bin/sh

php ./composer.phar config --no-plugins allow-plugins.php-http/discovery true &&
php ./composer.phar install &&
php bin/console doctrine:migrations:migrate
