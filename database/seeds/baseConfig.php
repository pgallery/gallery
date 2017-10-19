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
        $setting_group->setgroup_key  = 'base';
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

        $setting_group = new SettingsGroups();
        $setting_group->setgroup_key = 'comment';
        $setting_group->setgroup_name = 'Комментарии';
        $setting_group->setgroup_desc = 'Настройки комментариев';
        $setting_group->save();
        
        $comment = $setting_group->id;
        
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
            'set_value'   => '2010',
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
        Settings::create([
            'set_name'    => 'use_queue', 
            'set_value'   => 'no',
            'set_desc'    => 'Использовать обработчик очередей',
            'set_group'   => $base,
            'set_tooltip' => 'При включении данной опции все задачи по '
                . 'обработке изображений, такие как создание миниатюр и прочие, '
                . 'будут отправляться в менеджер очередей. Для обработки подобных '
                . 'очередей необходимо произвести дополнительную настройку галереи '
                . 'и запустить воркер (см. Документацию)',
            'set_type'    => 'yesno',
        ]);
        Settings::create([
            'set_name'    => 'registration', 
            'set_value'   => 'no',
            'set_desc'    => 'Разрешить регистрацию на сайте',
            'set_group'   => $auth,
            'set_tooltip' => 'Включение данной опции позволит Вашим посетителям '
                . 'регистрироваться на сайте. При регистрации пользователь получает'
                . 'права "Гость"',
            'set_type'    => 'yesno',
        ]);
        Settings::create([
            'set_name'    => 'archive_dir', 
            'set_value'   => 'gallery/archives',
            'set_desc'    => 'Директория для сохранения архивов',
            'set_group'   => $archive,
            'set_tooltip' => 'Директория, в которую сохраняются архивы, '
                . 'предоставляемые для скачивания.',
            'set_type'    => 'string',
        ]);
        Settings::create([
            'set_name'    => 'archive_save', 
            'set_value'   => '12',
            'set_desc'    => 'Время хранения архивов',
            'set_group'   => $archive,
            'set_tooltip' => 'Время, в часах, на которое сохраняется временный '
                . 'архив фотоальбома.',
            'set_type'    => 'numeric',
        ]);
        Settings::create([
            'set_name'    => 'use_transliterate', 
            'set_value'   => 'no',
            'set_desc'    => 'Использовать транслитирацию для файлов',
            'set_group'   => $upload,
            'set_tooltip' => 'Данноая опция включает транслитерацию имен файлов'
                . 'и директорий с русского на английский язык. Опция полезна в случае, '
                . 'если Ваш сервер не поддерживает кодировку файлов UTF (кирилица в именах '
                . 'загруженных файлов отображается на сервере в виде вопросительных'
                . 'знаком).',
            'set_type'    => 'yesno',
        ]);
        Settings::create([
            'set_name'    => 'format_bytes', 
            'set_value'   => 'bytes, Kb, Mb, Gb, Tb',
            'set_desc'    => 'Формат отображения объема файлов',
            'set_group'   => $view,
            'set_tooltip' => 'Данная опция позволяет указать в каком формате'
                . 'будет отображаться объем фотографии и альбома. Байты приобразуются'
                . 'в большие единицы измерения. Единицы измерения необходимо разделять'
                . 'запятой.',
            'set_type'    => 'string',
        ]);
        Settings::create([
            'set_name'    => 'format_precision', 
            'set_value'   => '2',
            'set_desc'    => 'Количество знаков после запятой для формата объема файлов',
            'set_group'   => $view,
            'set_tooltip' => 'Позволяет указать до какого количества знаков необходимо'
                . 'огруглять формат отображения объема файлов.',
            'set_type'    => 'numeric',
        ]);
        Settings::create([
            'set_name'    => 'comment_engine', 
            'set_value'   => 'None',
            'set_variations' => '{"None":"Выключен","Disqus":"Сервис Disqus","VK":"Сервис VK"}',
            'set_desc'    => 'Механизм комментариев',
            'set_group'   => $comment,
            'set_tooltip' => 'Выберите механизм комментариев.',
            
            'set_type'    => 'select',
        ]);
        Settings::create([
            'set_name'    => 'comment_disqus', 
            'set_value'   => '0000',
            'set_desc'    => 'Имя сайта в системе disqus.com',
            'set_group'   => $comment,
            'set_tooltip' => 'Выдается системой disqus.com после регистрации и добавления сайта.',
            'set_type'    => 'string',
        ]);
        Settings::create([
            'set_name'    => 'comment_vk', 
            'set_value'   => '0000',
            'set_desc'    => 'API ID в системе vk.com',
            'set_group'   => $comment,
            'set_tooltip' => 'Выдается системой vk.com при подключении виджета комментариев.',
            'set_type'    => 'string',
        ]);
    }
}
