<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Add2faVerifiedToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        \Schema::table('users', function (Blueprint $table) {
            $table->boolean('verified_2fa')->after('totp_secret')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        \Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verified_2fa');
        });
    }
}
