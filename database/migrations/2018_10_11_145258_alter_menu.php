<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;

class AlterMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu', function ($table) {
            $table->enum('type', ['tags', 'categories', 'year'])->after('name')->default('tags');
            $table->enum('show', ['Y', 'N'])->after('type')->default('Y');
            $table->smallInteger('sort')->after('show');
        });
        
        
        if(!Menu::where('type', 'categories')->exists()){
            Menu::create([
                'name' => 'Categories',
                'type' => 'categories',
                'show' => 'Y',
                'sort' => '1'
            ]);
        }
        
        if(!Menu::where('type', 'year')->exists()){       
            Menu::create([
                'name' => 'Year',
                'type' => 'year',
                'show' => 'Y',
                'sort' => '2'
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
        $categories = Menu::where('type', 'categories');
        $categories->forceDelete();

        $year = Menu::where('type', 'year');
        $year->forceDelete();
        
        Schema::table('menu', function ($table) {
            $table->dropColumn('type');
            $table->dropColumn('show');
            $table->dropColumn('sort');
        });
    }
}
