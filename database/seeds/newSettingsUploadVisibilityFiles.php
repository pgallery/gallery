<?php

use Illuminate\Database\Seeder;

use App\Models\SettingsGroups;
use App\Models\Settings;

class newSettingsUploadVisibilityFiles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting_group = SettingsGroups::where('setgroup_key', 'upload')->first();
        
        Settings::create([
            'set_name'    => 'saveinpublic_thumbs', 
            'set_value'   => 'no',
            'set_desc'    => 'Сохранение миниатюр в Web директории',
            'set_group'   => $setting_group->id,
            'set_tooltip' => 'При включении данной опции миниатюры публичных альбомов'
                . 'будут сохраняться на локальный диск в директорию доступную по HTTP (public/images).'
                . 'Это позволяет ускорить загрузку страниц галереи, а в случае использования AWS S3 '
                . 'хранилища сократит количество обращений к нему.',
            'set_type'    => 'yesno',
        ]);
        
        Settings::create([
            'set_name'    => 'saveinpublic_mobiles', 
            'set_value'   => 'no',
            'set_desc'    => 'Сохранение мобильной версии в Web директории',
            'set_group'   => $setting_group->id,
            'set_tooltip' => 'При включении данной опции мобильные версии изображений публичных альбомов'
                . 'будут сохраняться на локальный диск в директорию доступную по HTTP (public/images).'
                . 'Это позволяет ускорить загрузку страниц галереи, а в случае использования AWS S3 '
                . 'хранилища сократит количество обращений к нему.',
            'set_type'    => 'yesno',
        ]);
        
    }
}
