<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

use App\Models\Settings;

class AfterSettingsAddSettype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function($table) {
            $table->integer('set_type')->default('0')->after('set_desc');
            $table->integer('set_sort')->default('0')->after('set_type');
        });
        
	DB::table('settings')->where('set_name', 'gallery_name')->update(['set_sort' => 1]);
        
        DB::table('settings')->where('set_name', 'use_ssl')->update(['set_type'  => 1, 'set_sort' => 2]);
       
        DB::table('settings')->where('set_name', 'cache_ttl')->update(['set_sort' => 3]);
        
        DB::table('settings')->where('set_name', 'count_images')->update(['set_sort' => 4]);
        
        DB::table('settings')->where('set_name', 'mode_directory')->delete();

        DB::table('settings')->where('set_name', 'start_year')->update(['set_sort' => 5]);

        DB::table('settings')->where('set_name', 'upload_dir')->update(['set_sort' => 6]);

        DB::table('settings')->where('set_name', 'thumbs_dir')->update(['set_sort' => 7]);

        DB::table('settings')->where('set_name', 'mobile_upload_dir')->update(['set_sort' => 8]);

        DB::table('settings')->where('set_name', 'thumbs_width')->update(['set_sort' => 9]);

        DB::table('settings')->where('set_name', 'thumbs_height')->update(['set_sort' => 10]);

        DB::table('settings')->where('set_name', 'mobile_width')->update(['set_sort' => 11]);
       
        DB::table('settings')->where('set_name', 'use_ulogin')->update(['set_type'  => 1, 'set_sort' => 12]);

        DB::table('settings')->where('set_name', 'ulogin_id')->update(['set_sort' => 13]);
              
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function($table) {
            $table->dropColumn('set_type');
            $table->dropColumn('set_sort');
        });
    }
}
