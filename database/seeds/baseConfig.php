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
        $base   = SettingsGroups::where('setgroup_key', 'base')->first()->id;
        $auth   = SettingsGroups::where('setgroup_key', 'auth')->first()->id;
        $view   = SettingsGroups::where('setgroup_key', 'view')->first()->id;
        $upload = SettingsGroups::where('setgroup_key', 'upload')->first()->id;
        
        
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
                . 'авторизации через uLogin.ru требуется включить опцию "Авторизация через социальные сети"',
            'set_type'    => 'string',
        ]);        
//        Settings::create([
//            'set_name'    => 'use_queue', 
//            'set_value'   => 'no',
//            'set_desc'    => 'Использовать обработчик очередей',
//            'set_group'   => $base,
//            'set_tooltip' => 'При включении данной опции все задачи по '
//                . 'обработке изображений, такие как создание миниатюр и прочие, '
//                . 'будут отправляться в менеджер очередей. Для обработки подобных '
//                . 'очередей необходимо произвести дополнительную настройку галереи '
//                . 'и запустить воркер (см. Документацию)',
//            'set_type'    => 'yesno',
//        ]);                
//        Settings::create([
//            'set_name'    => 'registration', 
//            'set_value'   => 'no',
//            'set_desc'    => 'Разрешить регистрацию на сайте',
//            'set_group'   => $auth,
//            'set_tooltip' => 'Включение данной опции позволит Вашим посетителям '
//                . 'регистрироваться на сайте. При регистрации пользователь получает'
//                . 'права "Гость"',
//            'set_type'    => 'yesno',
//        ]);

    }
}
