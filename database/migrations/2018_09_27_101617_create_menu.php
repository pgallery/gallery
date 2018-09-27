<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('tags_menu', function (Blueprint $table) {
            $table->integer('menu_id')->unsigned();
            $table->integer('tags_id')->unsigned();

            $table->foreign('menu_id')->references('id')->on('menu')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tags_id')->references('id')->on('tags')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['menu_id', 'tags_id']);
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_menu');
        Schema::dropIfExists('menu');
    }
}
