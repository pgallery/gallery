<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Settings;

use Viewer;
use Cache;

class SettingsController extends Controller
{
    public function getSettings() {
        
        $SettingsQuery = Settings::all();
        
        foreach ($SettingsQuery as $setting) {
            $settings[] = [
                'set_name'  => $setting->set_name,
                'set_value' => $setting->set_value,
                'set_desc'  => $setting->set_desc,
            ];
        }
        
        return Viewer::get('admin.settings', [
            'settings' => $settings,
        ]);
    }
    
    public function putSettings(Request $request) {
        
        foreach ($request->input('newSetting') as $name => $value) {
            
            Settings::where('set_name', $name)->update(['set_value' => $value]);
            Cache::forget(sha1('global.settings'));
            
        }
        
        return redirect()->route('settings');
    }
    
    public function postSettings(Request $request) {
        
        Settings::create([
            'set_name'  => $request->input('key'),
            'set_value' => $request->input('value'),
            'set_desc'  => $request->input('desc'),
        ]);
        
        Cache::forget(sha1('global.settings'));
        
        return redirect()->route('settings');
    }
}
