<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('size')->default('NULL');
            $table->string('thumbs_size')->default('NULL');
            $table->string('modile_size')->default('NULL');
            $table->bigInteger('albums_id');
            $table->bigInteger('users_id')->default('1');
            $table->integer('is_rebuild')->default('0');
            $table->integer('is_thumbs')->default('0');
            $table->integer('is_modile')->default('0');
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
        Schema::dropIfExists('images');
    }
}
