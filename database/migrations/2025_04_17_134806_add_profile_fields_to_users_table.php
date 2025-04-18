<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('year')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('college')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['year', 'phone', 'role', 'profile_photo', 'college']);
        });
    }
}