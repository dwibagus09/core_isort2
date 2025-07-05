<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('site_id', 255);
            $table->string('department_name', 255);
            $table->string('telegram_channel_id', 255);
            $table->string('whatsapp_group_id', 255);
            $table->string('icon_menu', 255);
            $table->string('icon_thumbnail', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};

?>