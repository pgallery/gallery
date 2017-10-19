<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('set_name')->unique();
            $table->string('set_value');
            $table->string('set_variations')->nullable();
            $table->integer('set_group')->default('1');
            $table->string('set_desc');
            $table->mediumText('set_tooltip')->nullable();
            $table->enum('set_type', array('string', 'numeric', 'yesno', 'select'))->default('string');
            
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
        Schema::dropIfExists('settings');
    }
}
