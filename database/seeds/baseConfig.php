<?php

use Illuminate\Database\Seeder;

use App\Models\Settings;

class baseConfig extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create([
            'set_name'  => 'gallery_name', 
            'set_value' => 'My Gallery',
            'set_desc'  => 'Название галереи',
            'set_sort'  => 1,
        ]);        
        Settings::create([
            'set_name'  => 'use_ssl', 
            'set_value' => 'no',
            'set_desc'  => 'Принудительное перенаправление всех запросов на HTTPS',
            'set_type'  => 1,
            'set_sort'  => 2,
        ]);        
        Settings::create([
            'set_name'  => 'cache_ttl', 
            'set_value' => '3600',
            'set_desc'  => 'Время жизни (TTL) кэша страниц',
            'set_sort'  => 3,
        ]);        
        Settings::create([
            'set_name'  => 'count_images', 
            'set_value' => '24',
            'set_desc'  => 'Количество изображений на страницу',
            'set_sort'  => 4,
        ]);
        Settings::create([
            'set_name'  => 'start_year', 
            'set_value' => '2000',
            'set_desc'  => 'Минимальный год Ваших фотоальбомов',
            'set_sort'  => 5,
        ]);
        Settings::create([
            'set_name'  => 'upload_dir', 
            'set_value' => 'gallery/uploads',
            'set_desc'  => 'Директория для загрузки фотографий галереи',
            'set_sort'  => 6,
        ]);
        Settings::create([
            'set_name'  => 'thumbs_dir', 
            'set_value' => 'gallery/thumbs',
            'set_desc'  => 'Директория миниатюр',
            'set_sort'  => 7,
        ]);
        Settings::create([
            'set_name'  => 'mobile_upload_dir', 
            'set_value' => 'gallery/mobile',
            'set_desc'  => 'Директория галереи для мобильных устройств',
            'set_sort'  => 8,
        ]);
        Settings::create([
            'set_name'  => 'thumbs_width', 
            'set_value' => '180',
            'set_desc'  => 'Ширина изображений миниатюр',
            'set_sort'  => 9,
        ]);
        Settings::create([
            'set_name'  => 'thumbs_height', 
            'set_value' => '180',
            'set_desc'  => 'Высота изображений миниатюр',
            'set_sort'  => 10,
        ]);
        Settings::create([
            'set_name'  => 'mobile_width', 
            'set_value' => '1024',
            'set_desc'  => 'Ширина изображений мобильной версии',
            'set_sort'  => 11,
        ]);        
        Settings::create([
            'set_name'  => 'use_ulogin', 
            'set_value' => 'no',
            'set_desc'  => 'Использовать авторизацию через социальные сети (uLogin.ru)',
            'set_type'  => 1,
            'set_sort'  => 12,
        ]);
        Settings::create([
            'set_name'  => 'ulogin_id', 
            'set_value' => '0000',
            'set_desc'  => 'ID Вашего сайта в системе uLogin.ru (требуется только при use_ulogin == yes)',
            'set_sort'  => 13,
        ]);        
        Settings::create([
            'set_name'  => 'use_queue', 
            'set_value' => 'no',
            'set_desc'  => 'Использовать обработчик очередей',
            'set_type'  => 1,
            'set_sort'  => 14,
        ]);
        Settings::create([
            'set_name'  => 'registration', 
            'set_value' => 'no',
            'set_desc'  => 'Разрешить регистрацию на сайте',
            'set_type'  => 1,
            'set_sort'  => 15,
        ]);        
        
        
    }
}
