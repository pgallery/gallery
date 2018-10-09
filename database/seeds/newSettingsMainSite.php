<?php

use Illuminate\Database\Seeder;

class newSettingsMainSite extends Seeder
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
