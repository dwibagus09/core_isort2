<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdminsTable extends Migration
{
    public function up()
    {
        Schema::create('user_admins', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 255);
            $table->string('email', 100)->nullable();
            $table->string('password', 255);
            $table->string('name', 255);
            $table->string('phone_no', 255)->nullable();
            $table->string('photo', 200)->nullable();
            $table->string('role_id', 255)->nullable();
            $table->string('site_id', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_admins');
    }
};

?>