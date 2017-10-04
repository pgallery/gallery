<?php

use Illuminate\Database\Seeder;

use App\Models\SettingsGroups;
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
        
        $setting_group = new SettingsGroups();
        $setting_group->setgroup_key = 'base';
        $setting_group->setgroup_name = 'Общие';
        $setting_group->setgroup_desc = 'Общие настройки галереи';
        $setting_group->save();
        
        $base   = $setting_group->id;
        
        $setting_group = new SettingsGroups();
        $setting_group->setgroup_key  = 'auth';
        $setting_group->setgroup_name = 'Авторизация';
        $setting_group->setgroup_desc = 'Настройки авторизации и регистрации пользователей';
        $setting_group->save();
        
        $auth = $setting_group->id;
        
        $setting_group = new SettingsGroups();
        $setting_group->setgroup_key  = 'upload';
        $setting_group->setgroup_name = 'Загрузка';
        $setting_group->setgroup_desc = 'Настройки загрузки изображений';
        $setting_group->save();
        
        $upload = $setting_group->id;
        
        $setting_group = new SettingsGroups();
        $setting_group->setgroup_key  = 'view';
        $setting_group->setgroup_name = 'Отображение';
        $setting_group->setgroup_desc = 'Настройки отображения страниц и фотографий';
        $setting_group->save();        
        
        $view = $setting_group->id;
        
        $setting_group = new SettingsGroups();
        $setting_group->setgroup_key = 'archive';
        $setting_group->setgroup_name = 'Архивация';
        $setting_group->setgroup_desc = 'Настройки архивации фотоальбомов';
        $setting_group->save();
        
        $archive = $setting_group->id;
        
        Settings::create([
            'set_name'    => 'gallery_name', 
            'set_value'   => 'My Gallery',
            'set_desc'    => 'Название галереи',
            'set_group'   => $base,
            'set_tooltip' => "Название Вашей галереи, отображается в верхней части сайта.",
            'set_type'    => 'string',
        ]);
        Settings::create([
            'set_name'    => 'use_ssl', 
            'set_value'   => 'no',
            'set_desc'    => 'Принудительное перенаправление всех запросов на HTTPS',
            'set_group'   => $base,
            'set_tooltip' => "Включает принудительное перенаправление всех HTTP запросов "
                . "на HTTPS. Для корректной работы требуется верифицированный SSL сертификат.",
            'set_type'    => 'yesno',
        ]);        
        Settings::create([
            'set_name'    => 'cache_ttl', 
            'set_value'   => '3600',
            'set_desc'    => 'Время жизни (TTL) кэша страниц',
            'set_group'   => $view,
            'set_tooltip' => "Время, в секундах, на которое сохраняется кэш данных.",
            'set_type'    => 'numeric',
        ]);
        Settings::create([
            'set_name'    => 'count_images', 
            'set_value'   => '24',
            'set_desc'    => 'Количество изображений на страницу',
            'set_group'   => $view,
            'set_tooltip' => "Количество изображений, выводимых в списке при "
                . "просмотре содержимого альбома.",
            'set_type'    => 'numeric',
        ]);
        Settings::create([
            'set_name'    => 'start_year', 
            'set_value'   => '2000',
            'set_desc'    => 'Минимальный год Ваших фотоальбомов',
            'set_group'   => $view,
            'set_tooltip' => 'Год, с которого начинаются Ваши фотоальбомы в галереи. '
                . 'Используется для автоматического построения выбора года при '
                . 'создании альбома.',
            'set_type'    => 'numeric',
        ]);
        Settings::create([
            'set_name'    => 'upload_dir', 
            'set_value'   => 'gallery/uploads',
            'set_desc'    => 'Директория для загрузки фотографий галереи',
            'set_group'   => $upload,
            'set_tooltip' => 'Директория, относительно public, в которую будут '
                . 'загружаться Ваши фотографии. Web-сервер должен иметь возможность'
                . 'производить в неё запись.',
            'set_type'    => 'string',
        ]);
        Settings::create([
            'set_name'    => 'thumbs_dir', 
            'set_value'   => 'gallery/thumbs',
            'set_desc'    => 'Директория миниатюр',
            'set_group'   => $upload,
            'set_tooltip' => 'Директория, в которую будут создаваться миниатюры '
                . 'фотографий.  Web-сервер должен иметь возможность производить '
                . 'в неё запись.',
            'set_type'    => 'string',
        ]);
        Settings::create([
            'set_name'    => 'mobile_upload_dir', 
            'set_value'   => 'gallery/mobile',
            'set_desc'    => 'Директория галереи для мобильных устройств',
            'set_group'   => $upload,
            'set_tooltip' => 'Директория, в которую будут создаваться уменьшенные'
                . ' копии фотографий, отображаемые для мобильных устройств.  '
                . 'Web-сервер должен иметь возможность производить в неё запись.',
            'set_type'    => 'string',
        ]);
        Settings::create([
            'set_name'    => 'thumbs_width', 
            'set_value'   => '180',
            'set_desc'    => 'Ширина изображений миниатюр',
            'set_group'   => $view,
            'set_tooltip' => 'Ширина изображений миниатюр',
            'set_type'    => 'numeric',
        ]);
        Settings::create([
            'set_name'    => 'thumbs_height', 
            'set_value'   => '180',
            'set_desc'    => 'Высота изображений миниатюр',
            'set_group'   => $view,
            'set_tooltip' => 'Высота изображений миниатюр',
            'set_type'    => 'numeric',
        ]);
        Settings::create([
            'set_name'    => 'mobile_width', 
            'set_value'   => '1024',
            'set_desc'    => 'Ширина изображений мобильной версии',
            'set_group'   => $view,
            'set_tooltip' => 'Ширина изображений мобильной версии',
            'set_type'    => 'numeric',
        ]);        
        Settings::create([
            'set_name'    => 'use_ulogin', 
            'set_value'   => 'no',
            'set_desc'    => 'Авторизация через социальные сети',
            'set_group'   => $auth,
            'set_tooltip' => 'При включении данной опции на Вашем сайте появится '
                . 'возможность проходить авторизацию через социальные сети. '
                . 'Используется сервис uLogin.ru. Требуется указать "ID сайта на uLogin.ru"',
            'set_type'    => 'yesno',
        ]);
        Settings::create([
            'set_name'    => 'ulogin_id', 
            'set_value'   => '0000',
            'set_desc'    => 'ID сайта на uLogin.ru',
            'set_group'   => $auth,
            'set_tooltip' => 'ID Вашегой сайта в системе uLogin.ru. Для работы '
                . 'авторизации через uLogin.ru требуется включить опцию '
                . '"Авторизация через социальные сети"',
            'set_type'    => 'string',
        ]);        

        $setting = new App\Models\Settings();
        $setting->set_name    = 'use_queue';
        $setting->set_value   = 'no';
        $setting->set_group   = $base;
        $setting->set_desc    = 'Использовать обработчик очередей';
        $setting->set_tooltip = 'При включении данной опции все задачи по '
                . 'обработке изображений, такие как создание миниатюр и прочие, '
                . 'будут отправляться в менеджер очередей. Для обработки подобных '
                . 'очередей необходимо произвести дополнительную настройку галереи '
                . 'и запустить воркер (см. Документацию)';
        $setting->set_type    = 'yesno';
        $setting->save();

        $setting = new App\Models\Settings();
        $setting->set_name    = 'registration';
        $setting->set_value   = 'no';
        $setting->set_group   = $auth;
        $setting->set_desc    = 'Разрешить регистрацию на сайте';
        $setting->set_tooltip =  'Включение данной опции позволит Вашим посетителям '
                . 'регистрироваться на сайте. При регистрации пользователь получает'
                . 'права "Гость"';
        $setting->set_type    = 'yesno';
        $setting->save();             
        
        $setting = new App\Models\Settings();
        $setting->set_name    = 'archive_dir';
        $setting->set_value   = 'gallery/archives';
        $setting->set_group   = $archive;
        $setting->set_desc    = 'Директория для сохранения архивов';
        $setting->set_tooltip = 'Директория, в которую сохраняются архивы, '
                . 'предоставляемые для скачивания.';
        $setting->set_type    = 'string';
        $setting->save();
        
        $setting = new App\Models\Settings();
        $setting->set_name    = 'archive_save';
        $setting->set_value   = '12';
        $setting->set_group   = $archive;
        $setting->set_desc    = 'Время хранения архивов';
        $setting->set_tooltip = 'Время, в часах, на которое сохраняется временный '
                . 'архив фотоальбома.';
        $setting->set_type    = 'numeric';
        $setting->save();        
        
    }
}
