<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call(baseConfig::class);
        $this->call(baseRoles::class);
        $this->call(baseUsers::class);
        $this->call(newSettingsUploadVisibilityFiles::class);
        $this->call(newSettingsGalleryDescription::class);
        $this->call(newSettingsMainSite::class);
        $this->call(newSettingsYandexMetrika::class);
        
    }
}
