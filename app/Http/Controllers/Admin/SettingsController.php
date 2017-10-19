<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SettingsGroups;
use App\Models\Settings;

use Viewer;
use Cache;

class SettingsController extends Controller
{
    protected $settings;
    protected $settings_groups;

    public function __construct(SettingsGroups $settings_groups, Settings $settings) {
        
        $this->middleware('g2fa');
        
        $this->settings         = $settings;
        $this->settings_groups  = $settings_groups;
    }
    
    /*
     * Вывод страницы редактирования настроек
     */
    public function getSettings() {
    
        return Viewer::get('admin.setting.index', [
            'settings_groups'   => $this->settings_groups->all(),
            'settings'          => $this->settings->all(),
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
    
}
