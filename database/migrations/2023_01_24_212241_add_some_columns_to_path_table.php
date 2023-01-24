<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paths', function (Blueprint $table) {
            $table->string('user');
            $table->string('password');
            $table->ipAddress('host');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paths', function (Blueprint $table) {
            $table->dropColumn('user','password','host');
        });
    }
};
