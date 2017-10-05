<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingUseTransliterate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $upload = App\Models\SettingsGroups::where('setgroup_key', 'upload')->first()->id;
        
        $setting = new App\Models\Settings();
        $setting->set_name    = 'use_transliterate';
        $setting->set_value   = 'no';
        $setting->set_group   = $upload;
        $setting->set_desc    = 'Использовать транслитирацию для файлов';
        $setting->set_tooltip = 'Данноая опция включает транслитерацию имен файлов'
                . 'и директорий с русского на английский язык. Опция полезна в случае, '
                . 'если Ваш сервер не поддерживает кодировку файлов UTF (кирилица в именах '
                . 'загруженных файлов отображается на сервере в виде вопросительных'
                . 'знаком).';
        $setting->set_type    = 'yesno';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        App\Models\Settings::where('set_name', 'use_transliterate')->delete();
    }
}
