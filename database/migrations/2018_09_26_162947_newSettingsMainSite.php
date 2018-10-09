<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\SettingsGroups;
use App\Models\Settings;

class NewSettingsMainSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(SettingsGroups::where('setgroup_key', 'base')->exists()){
        
            $setting_group = SettingsGroups::where('setgroup_key', 'base')->first();

            Settings::create([
                'set_name'    => 'main_site', 
                'set_value'   => 'null',
                'set_desc'    => 'Адрес основного сайта',
                'set_group'   => $setting_group->id,
                'set_tooltip' => "Если Вы укажите адрес основного сайта, то в главном меню появится на него ссылка. Адрес ".
                "сайта должен начинаться с http:// или https://",
                'set_type'    => 'string',
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
        Settings::where('set_name', 'main_site')->delete();
    }
}
