<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('setgroup_key')->unique();
            $table->string('setgroup_name');
            $table->string('setgroup_desc');
            $table->timestamps();
        });
        
        Schema::table('settings', function($table) {
            $table->dropColumn('set_sort');
            $table->integer('set_group')->default('1')->after('set_value');
            $table->mediumText('set_tooltip')->nullable()->after('set_desc');
        });        
        
//        DB::statement("ALTER TABLE `settings` MODIFY COLUMN `set_type` enum('string', 'numeric', 'yesno') NOT NULL DEFAULT 'string';");
        
        Schema::table('settings', function (Blueprint $table) {
            $table->enum('set_type', array('string', 'numeric', 'yesno'))->default('string')->change();
        });
        
        $setting_group = new App\Models\SettingsGroups();
        $setting_group->setgroup_key = 'base';
        $setting_group->setgroup_name = 'Общие';
        $setting_group->setgroup_desc = 'Общие настройки галереи';
        $setting_group->save();

        $setting_group = new App\Models\SettingsGroups();
        $setting_group->setgroup_key  = 'auth';
        $setting_group->setgroup_name = 'Авторизация';
        $setting_group->setgroup_desc = 'Настройки авторизации и регистрации пользователей';
        $setting_group->save();
        
        $setting_group = new App\Models\SettingsGroups();
        $setting_group->setgroup_key  = 'upload';
        $setting_group->setgroup_name = 'Загрузка';
        $setting_group->setgroup_desc = 'Настройки загрузки изображений';
        $setting_group->save();
        
        $setting_group = new App\Models\SettingsGroups();
        $setting_group->setgroup_key  = 'view';
        $setting_group->setgroup_name = 'Отображение';
        $setting_group->setgroup_desc = 'Настройки отображения страниц и фотографий';
        $setting_group->save();
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_groups');
        
        Schema::table('settings', function($table) {
            $table->integer('set_sort')->default('0')->after('set_type');
            $table->dropColumn('set_group');
            $table->dropColumn('set_tooltip');
        });
        
        DB::statement("ALTER TABLE `settings` CHANGE `set_type` `set_type` INT(10) NULL DEFAULT NULL;");
        
    }
}
