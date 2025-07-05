<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration {
    
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id'); // Auto Increment Primary Key
            $table->string('role', 255)->collation('latin1_swedish_ci');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();

            // Foreign Key jika category_id berhubungan dengan tabel lain (misalnya categories)
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
?>