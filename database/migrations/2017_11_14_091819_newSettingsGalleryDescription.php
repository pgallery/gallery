<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\SettingsGroups;
use App\Models\Settings;

class NewSettingsGalleryDescription extends Migration
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
                'set_name'    => 'gallery_description', 
                'set_value'   => 'Краткое описание галереи',
                'set_desc'    => 'Краткое описание галереи',
                'set_group'   => $setting_group->id,
                'set_tooltip' => 'Краткое описание, чему посвящена Ваша галерея. '
                    . 'Опция используется для построения META-тегов.',
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
        Settings::where('set_name', 'gallery_description')->delete();
    }
}
