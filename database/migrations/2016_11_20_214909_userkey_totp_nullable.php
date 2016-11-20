<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserkeyTotpNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_key', 'totp_secret']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->binary('user_key')->nullable();
            $table->binary('totp_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_key', 'totp_secret']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->binary('user_key');
            $table->binary('totp_secret');
        });
    }
}
