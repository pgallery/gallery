<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('tags_albums', function (Blueprint $table) {
            $table->integer('albums_id')->unsigned();
            $table->integer('tags_id')->unsigned();

            $table->foreign('albums_id')->references('id')->on('albums')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tags_id')->references('id')->on('tags')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['albums_id', 'tags_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_albums');
        Schema::dropIfExists('tags');
    }
}
