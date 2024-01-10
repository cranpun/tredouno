
rm database\database.sqlite;
New-Item -ItemType "file" database\database.sqlite;
php artisan migrate;
php artisan db:seed;
