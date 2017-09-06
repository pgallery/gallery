# Простая фотогалерея

Используется Laravel 5 и Bootstrap 3. Выполнено в личных целях. Использование на 
свой страх и риск. Все фотографии храняться в открытом доступе.

Полно багов, многое не работает, находится в разработке.

### Требования

 - **PHP:** 7.0 и выше.
 - **База данных:** MySQL 5.5 и выше или PostgreSQL 9.4 и выше.
 - **Кэш:** Локальный диск или Memcached (остальные не проверялись).

### Рекомендуемые параметры PHP

 - **max_execution_time**: 360
 - **max_file_uploads**: 250
 - **memory_limit**: 256Mb
 - **post_max_size**: 256Mb
 - **upload_max_filesize**: 256Mb

### Установка

```
git clone https://github.com/Dasvasas/gallery.git
cd gallery
composer install
composer update
cp .env.example .env
/opt/php71/bin/php artisan key:generate
/opt/php71/bin/php artisan migrate
/opt/php71/bin/php artisan db:seed
/opt/php71/bin/php artisan route:cache
```

### Обновление

```
git pull
composer update
/opt/php71/bin/php artisan migrate
/opt/php71/bin/php artisan route:clear
/opt/php71/bin/php artisan cache:clear
/opt/php71/bin/php artisan route:cache
```

### Необходимо прописать в crontab

```
* * * * * /opt/php71/bin/php PATH/TO/artisan schedule:run >/dev/null 2>&1
```

### Роли пользователей

| Наименование | Доступ в админку | Возможности |
|----------|-----|----------------------|
|**admin**| **Да**| Выполнение всех действий |
|**moderator**| **Да**| Лишен возможностей управления группами и полного удаления объектов |
|**operator**| **Да**| Имеет возможность управления собственными альбомами и изображениями (не реализовано) |
|**viewer**| **Нет**| Просмотр с отображением скрытых альбомов (не реализовано) |
|**guest**| **Нет**| Только просмотр (выдается по умолчанию при регистрации) |

### Если стоит ISPmanager 5 lite

```
/opt/php71/bin/php /usr/local/bin/composer install
```

### Скриншоты

![AdminCreatePage](https://twh.club/wp-content/uploads/2017/05/gl_screen_createall.png)
