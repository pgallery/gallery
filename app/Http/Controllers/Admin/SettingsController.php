<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings;

use Viewer;
use Cache;

class SettingsController extends Controller
{
    /*
     * Вывод страницы редактирования настроек
     */
    public function getSettings() {
        
        $settings = Settings::all();
        
        return Viewer::get('admin.settings', [
            'settings' => $settings,
        ]);
    }
    
    /*
     * Сохранение изменений настроек
     */
    public function putSettings(Request $request) {
        
        foreach ($request->input('newSetting') as $name => $value) {
            Settings::where('set_name', $name)->update(['set_value' => $value]);
        }

        Cache::flush();

        return redirect()->route('settings');
    }
    
    /*
     * Добавление новых настроек
     */    
    public function postSettings(Request $request) {
        
        Settings::create($request->all());
        
        Cache::flush();
        
        return redirect()->route('settings');
    }
}
