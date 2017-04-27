<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\SettingsRequest;
use App\Http\Controllers\Controller;

use App\Models\Settings;

use Viewer;
use Cache;

class SettingsController extends Controller
{
    protected $settings;

    public function __construct(Settings $settings) {
        $this->settings  = $settings;
    }
    
    /*
     * Вывод страницы редактирования настроек
     */
    public function getSettings() {
        
        $settings = $this->settings->orderBy('set_desc')->get();
        
        return Viewer::get('admin.settings', [
            'settings' => $settings,
        ]);
    }
    
    /*
     * Сохранение изменений настроек
     */
    public function putSettings(Request $request) {
        
        foreach ($request->input('newSetting') as $name => $value) {
            $this->settings->where('set_name', $name)->update(['set_value' => $value]);
        }

        Cache::flush();

        return redirect()->route('settings');
    }
    
    /*
     * Добавление новых настроек
     */
    public function postSettings(SettingsRequest $request) {
        
        $this->settings->create($request->all());
        
        Cache::flush();
        
        return redirect()->route('settings');
    }
}
