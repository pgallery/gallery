# Простая фотогалерея

Используется Laravel 5 and Bootstrap 3. Выполнено в личных целях. Использование на свой страх и риск.

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
|**operator**| **Да**| Имеет возможность управления собственными альбомами и изображениями |
|**viewer**| **Нет**| Просмотр с отображением скрытых альбомов |
|**guest**| **Нет**| Только просмотр (выдается по умолчанию при регистрации) |

