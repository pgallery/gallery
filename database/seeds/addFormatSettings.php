<?php

use Illuminate\Database\Seeder;

class addFormatSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $view   = App\Models\SettingsGroups::where('setgroup_key', 'view')->first()->id;
        
        $setting = new App\Models\Settings();
        $setting->set_name    = 'format_bytes';
        $setting->set_value   = 'bytes, Kb, Mb, Gb, Tb';
        $setting->set_group   = $view;
        $setting->set_desc    = 'Формат отображения объема файлов';
        $setting->set_tooltip = 'Данная опция позволяет указать в каком формате'
                . 'будет отображаться объем фотографии и альбома. Байты приобразуются'
                . 'в большие единицы измерения. Единицы измерения необходимо разделять'
                . 'запятой.';
        $setting->set_type    = 'string';
        $setting->save();
        
        $setting = new App\Models\Settings();
        $setting->set_name    = 'format_precision';
        $setting->set_value   = '2';
        $setting->set_group   = $view;
        $setting->set_desc    = 'Количество знаков после запятой для формата объема файлов';
        $setting->set_tooltip = 'Позволяет указать до какого количества знаков необходимо'
                . 'огруглять формат отображения объема файлов.';
        $setting->set_type    = 'numeric';
        $setting->save();  
    }
}
