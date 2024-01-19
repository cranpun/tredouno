#cd ..; \
php artisan  clear-compiled;
php artisan  auth:clear-resets;
php artisan  cache:clear;
php artisan  config:clear;
php artisan  route:clear;
php artisan  view:clear;
php artisan  config:cache;
php docker/composer.phar dump-autoload;
#cd bin;
