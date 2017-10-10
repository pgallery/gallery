<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(App\Models\SettingsGroups::where('setgroup_key', 'view')->first()){
            Artisan::call( 'db:seed', [
                '--class' => 'addFormatSettings',
                '--force' => true 
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
        App\Models\Settings::where('set_name', 'format_bytes')->delete();
        App\Models\Settings::where('set_name', 'format_precision')->delete();   
    }
}
