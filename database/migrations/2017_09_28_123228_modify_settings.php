<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $base   = App\Models\SettingsGroups::where('setgroup_key', 'base')->first()->id;
        $auth   = App\Models\SettingsGroups::where('setgroup_key', 'auth')->first()->id;
        $view   = App\Models\SettingsGroups::where('setgroup_key', 'view')->first()->id;
        $upload = App\Models\SettingsGroups::where('setgroup_key', 'upload')->first()->id;
        
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
        
        $setting = App\Models\Settings::where('set_name', 'gallery_name')->update([
            'set_group'   => $base,
            'set_tooltip' => "Название Вашей галереи, отображается в верхней части сайта.",
            'set_type'    => 'string',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'use_ssl')->update([
            'set_group'   => $base,
            'set_tooltip' => "Включает принудительное перенаправление всех HTTP запросов "
                . "на HTTPS. Для корректной работы требуется верифицированный SSL сертификат.",
            'set_type'    => 'yesno',
        ]);        
        
        $setting = App\Models\Settings::where('set_name', 'cache_ttl')->update([
            'set_group'   => $view,
            'set_tooltip' => "Время, в секундах, на которое сохраняется кэш данных.",
            'set_type'    => 'numeric',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'count_images')->update([
            'set_group'   => $view,
            'set_tooltip' => "Количество изображений, выводимых в списке при "
                . "просмотре содержимого альбома.",
            'set_type'    => 'numeric',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'start_year')->update([
            'set_group'   => $view,
            'set_tooltip' => 'Год, с которого начинаются Ваши фотоальбомы в галереи. '
                . 'Используется для автоматического построения выбора года при '
                . 'создании альбома.',
            'set_type'    => 'numeric',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'upload_dir')->update([
            'set_group'   => $upload,
            'set_tooltip' => 'Директория, относительно public, в которую будут '
                . 'загружаться Ваши фотографии. Web-сервер должен иметь возможность'
                . 'производить в неё запись.',
            'set_type'    => 'string',
        ]);

        $setting = App\Models\Settings::where('set_name', 'thumbs_dir')->update([
            'set_group'   => $upload,
            'set_tooltip' => 'Директория, в которую будут создаваться миниатюры '
                . 'фотографий.  Web-сервер должен иметь возможность производить '
                . 'в неё запись.',
            'set_type'    => 'string',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'mobile_upload_dir')->update([
            'set_group'   => $upload,
            'set_tooltip' => 'Директория, в которую будут создаваться уменьшенные'
                . ' копии фотографий, отображаемые для мобильных устройств.  '
                . 'Web-сервер должен иметь возможность производить в неё запись.',
            'set_type'    => 'string',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'thumbs_width')->update([
            'set_group'   => $view,
            'set_tooltip' => 'Ширина изображений миниатюр',
            'set_type'    => 'numeric',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'thumbs_height')->update([
            'set_group'   => $view,
            'set_tooltip' => 'Высота изображений миниатюр',
            'set_type'    => 'numeric',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'mobile_width')->update([
            'set_group'   => $view,
            'set_tooltip' => 'Ширина изображений мобильной версии',
            'set_type'    => 'numeric',
        ]);
        
        $setting = App\Models\Settings::where('set_name', 'use_ulogin')->update([
            'set_group'   => $auth,
            'set_tooltip' => 'При включении данной опции на Вашем сайте появится '
                . 'возможность проходить авторизацию через социальные сети. '
                . 'Используется сервис uLogin.ru. Требуется указать "ID сайта на uLogin.ru"',
            'set_type'    => 'yesno',
        ]); 
        
        $setting = App\Models\Settings::where('set_name', 'ulogin_id')->update([
            'set_group'   => $auth,
            'set_tooltip' => 'ID Вашегой сайта в системе uLogin.ru. Для работы '
                . 'авторизации через uLogin.ru требуется включить опцию '
                . '"Авторизация через социальные сети"',
            'set_type'    => 'string',
        ]);        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        App\Models\Settings::where('set_name', 'use_queue')->delete();
        App\Models\Settings::where('set_name', 'registration')->delete(); 
    }
}
