<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

//use App\Models\User;
//use Google2FA;

class AfterGoogle2faKeyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('users', function (Blueprint $table) {
	    	$table->boolean('google2fa_enabled')->default(false)->after('method');
	    	$table->timestamp('google2fa_ts')->nullable()->after('google2fa_enabled');
		$table->string('google2fa_secret')->nullable()->after('google2fa_ts');
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
