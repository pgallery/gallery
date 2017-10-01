<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('size')->default('0');
            $table->bigInteger('users_id');
            $table->bigInteger('albums_id');
            $table->softDeletes();
            $table->timestamps();
        });
        
        $setting_group = new App\Models\SettingsGroups();
        $setting_group->setgroup_key = 'archive';
        $setting_group->setgroup_name = 'Архивация';
        $setting_group->setgroup_desc = 'Настройки архивации фотоальбомов';
        $setting_group->save();
        
        $setting = new App\Models\Settings();
        $setting->set_name    = 'archive_dir';
        $setting->set_value   = 'gallery/archives';
        $setting->set_group   = $setting_group->id;
        $setting->set_desc    = 'Директория для сохранения архивов';
        $setting->set_tooltip = 'Директория, в которую сохраняются архивы, '
                . 'предоставляемые для скачивания.';
        $setting->set_type    = 'string';
        $setting->save();
        
        $setting = new App\Models\Settings();
        $setting->set_name    = 'archive_save';
        $setting->set_value   = '12';
        $setting->set_group   = $setting_group->id;
        $setting->set_desc    = 'Время хранения архивов';
        $setting->set_tooltip = 'Время, в часах, на которое сохраняется временный '
                . 'архив фотоальбома.';
        $setting->set_type    = 'numeric';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archives');
        
        App\Models\Settings::where('set_name', 'archive_dir')->delete();
        App\Models\Settings::where('set_name', 'archive_save')->delete();
    }
}
