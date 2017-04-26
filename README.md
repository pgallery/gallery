# Простая фотогалерея

Используется Laravel 5 и Bootstrap 3. Выполнено в личных целях. Использование на свой страх и риск.

Полно багов, многое не работает, находится в разработке.

### Требования

PHP 7.0 и выше.

### Установка

```
git clone https://github.com/Dasvasas/gallery.git
composer install
composer update
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Обновление

```
git pull
composer update
php artisan migrate
php artisan cache:clear
```

### Необходимо прописать в crontab

```
* * * * * /usr/bin/php PATH/TO/artisan schedule:run >/dev/null 2>&1
```

### Роли пользователей

| Наименование | Доступ в админку | Возможности |
|----------|-----|----------------------|
|**admin**| **Да**| Выполнение всех действий |
|**moderator**| **Да**| Лишен возможностей управления группами и полного удаления объектов |
|**operator**| **Да**| Имеет возможность управления собственными альбомами и изображениями (не реализовано) |
|**viewer**| **Нет**| Просмотр с отображением скрытых альбомов (не реализовано) |
|**guest**| **Нет**| Только просмотр (выдается по умолчанию при регистрации) |

### Рекомендуемые параметры PHP

max_execution_time = 360
max_file_uploads = 250
memory_limit = 256Mb
post_max_size = 256Mb
upload_max_filesize = 256Mb

### Если стоит ISPmanager 5 lite

```
/opt/php71/bin/php /usr/local/bin/composer install
```