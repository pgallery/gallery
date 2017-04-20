# gallery

Laravel 5 and Bootstrap 3

### install

```
git clone https://github.com/Dasvasas/gallery.git
composer install
composer update
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### update

```
git pull
php artisan migrate
php artisan cache:clear
```

### crontab

```
* * * * * /usr/bin/php PATH/TO/artisan schedule:run >/dev/null 2>&1
```