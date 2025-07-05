<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessControlTable extends Migration {
    
    public function up()
    {
        Schema::create('access_control', function (Blueprint $table) {
            $table->id('access_control_id'); // Primary Key Auto Increment
            $table->unsignedBigInteger('role_id')->nullable();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->integer('read_access')->default(0); // Tidak boleh null, default 0
            $table->integer('write_access')->default(0); // Tidak boleh null, default 0
            $table->timestamps();

            // Foreign Key (Jika role_id dan module_id berhubungan dengan tabel lain)
            // $table->foreign('role_id')->references('role_id')->on('roles')->onDelete('set null');
            // $table->foreign('module_id')->references('module_id')->on('modules')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_control');
    }
};
?>