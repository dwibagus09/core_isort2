<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsTable extends Migration
{
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->integer('department_id');
            $table->string('incident_name', 255);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};

?>