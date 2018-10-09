<?php

use Illuminate\Database\Seeder;

use App\Models\SettingsGroups;
use App\Models\Settings;

class newSettingsGalleryDescription extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
