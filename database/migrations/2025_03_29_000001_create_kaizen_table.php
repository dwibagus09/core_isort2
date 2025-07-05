<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaizenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kaizen', function (Blueprint $table) {
            $table->bigIncrements('kaizen_id');
            $table->string('picture', 255);
            $table->text('location');
            $table->text('description');
            $table->datetime('issue_date')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->string('solved_picture', 255)->nullable();
            $table->datetime('solved_date')->nullable();
            $table->integer('kaizen_type_id')->nullable();
            $table->text('status')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('mod_report_id')->nullable();
            $table->integer('site_id')->nullable();
            $table->integer('kejadian_id')->nullable();
            $table->integer('modus_id')->nullable();
            $table->integer('floor_id')->nullable();
            $table->integer('manpower_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('kaizen_type2_id')->nullable();
            
            // Jika Anda ingin menambahkan timestamps (created_at, updated_at)
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kaizen');
    }
}