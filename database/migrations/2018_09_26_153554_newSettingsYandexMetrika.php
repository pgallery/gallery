<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\SettingsGroups;
use App\Models\Settings;

class NewSettingsYandexMetrika extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(SettingsGroups::where('setgroup_key', 'yandexmetrika')->exists()){
            
            $yandexmetrika_group = new SettingsGroups();
            $yandexmetrika_group->setgroup_key  = 'yandexmetrika';
            $yandexmetrika_group->setgroup_name = 'Яндекс.Метрика';
            $yandexmetrika_group->setgroup_desc = 'Настройка счетчика Яндекс.Метрика';
            $yandexmetrika_group->save();

            $yandexmetrika   = $yandexmetrika_group->id;

            Settings::create([
                'set_name'    => 'use_yandexmetrika', 
                'set_value'   => 'no',
                'set_desc'    => 'Яндекс.Метрика',
                'set_group'   => $yandexmetrika,
                'set_tooltip' => "Включить счетчик Яндекс.Метрика.",
                'set_type'    => 'yesno',
            ]);
            Settings::create([
                'set_name'    => 'yandexmetrika_id', 
                'set_value'   => '0000',
                'set_desc'    => 'ID счетчика',
                'set_group'   => $yandexmetrika,
                'set_tooltip' => "ID Вашего счетчика.",
                'set_type'    => 'numeric',
            ]);
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Settings::where('set_name', 'use_yandexmetrika')->delete();
        Settings::where('set_name', 'yandexmetrika_id')->delete();
        SettingsGroups::where('setgroup_key', 'yandexmetrika')->delete();
    }
}
