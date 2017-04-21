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
            'set_name' => 'upload_dir', 
            'set_value' => 'gallery/uploads',
            'set_desc' => 'Директория для загрузки фотографий галереи',
        ]);
        Settings::create([
            'set_name' => 'thumbs_dir', 
            'set_value' => 'gallery/thumbs',
            'set_desc' => 'Директория миниатюр',
        ]);
        Settings::create([
            'set_name' => 'mobile_upload_dir', 
            'set_value' => 'gallery/mobile',
            'set_desc' => 'Директория галереи для мобильных устройств',
        ]);
        Settings::create([
            'set_name' => 'thumbs_width', 
            'set_value' => '180',
            'set_desc' => 'Ширина изображений миниатюр',
        ]);
        Settings::create([
            'set_name' => 'thumbs_height', 
            'set_value' => '180',
            'set_desc' => 'Высота изображений миниатюр',
        ]);
        Settings::create([
            'set_name' => 'mobile_width', 
            'set_value' => '1024',
            'set_desc' => 'Ширина изображений мобильной версии',
        ]);
        Settings::create([
            'set_name' => 'cache_ttl', 
            'set_value' => '3600',
            'set_desc' => 'Время жизни (TTL) кэша страниц',
        ]);
        Settings::create([
            'set_name' => 'mode_directory', 
            'set_value' => '0755',
            'set_desc' => 'Права для создаваемых директорий (не используется)',
        ]);
        Settings::create([
            'set_name' => 'gallery_name', 
            'set_value' => 'TWH.Gallery',
            'set_desc' => 'Название галереи',
        ]);
        Settings::create([
            'set_name' => 'use_ssl', 
            'set_value' => 'no',
            'set_desc' => 'Принудительное перенаправление всех запросов на HTTPS',
        ]);
        Settings::create([
            'set_name' => 'use_ulogin', 
            'set_value' => 'no',
            'set_desc' => 'Использовать авторизацию через социальные сети (uLogin.ru)',
        ]);        
        Settings::create([
            'set_name' => 'ulogin_id', 
            'set_value' => '0000',
            'set_desc' => 'ID Вашего сайта в системе uLogin.ru (требуется только при включенной авторизации через uLogin.ru)',
        ]);          
        
    }
}
