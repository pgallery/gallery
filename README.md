# gallery

```
git clone https://github.com/Dasvasas/gallery.git
composer install
composer update
cp .env.example .env
php artisan key:generate
```

### crontab

```
* * * * * /usr/bin/php PATH/TO/artisan schedule:run >/dev/null 2>&1
```