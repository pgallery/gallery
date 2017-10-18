<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('url')->default('NULL');
            $table->string('directory')->default('NULL');
            $table->bigInteger('images_id')->default('0');
            $table->bigInteger('year')->default('1');
            $table->string('desc')->default('NULL');
            $table->enum('permission', array('All', 'Url', 'Pass'))->default('All');
            $table->bigInteger('categories_id')->default('1');
            $table->bigInteger('users_id')->default('1');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
    }
}
