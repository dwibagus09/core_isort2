<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('access_control_modules', function (Blueprint $table) {
            $table->id('module_id'); // Primary Key Auto Increment
            $table->string('menu_name', 255)->collation('latin1_swedish_ci'); // Wajib diisi
            $table->string('submenu_name', 255)->collation('latin1_swedish_ci')->nullable(); // Bisa NULL
            $table->string('url', 255)->collation('latin1_swedish_ci'); // Wajib diisi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_control_modules');
    }
};

?>