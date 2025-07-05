<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist', function (Blueprint $table) {
            $table->bigIncrements('checklist_id');
            $table->integer('site_id')->nullable(false);
            $table->integer('template_id')->nullable(false);
            $table->string('room_no', 255)->nullable(false);
            $table->integer('user_id')->nullable();
            $table->datetime('submitted_date')->nullable();
            
            // Tambahkan indeks untuk kolom yang sering di-query
            $table->index('site_id');
            $table->index('template_id');
            $table->index('user_id');
            
            // Jika ingin menambahkan timestamps
            // $table->timestamps();
            
            // Jika ingin menambahkan soft deletes
            // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist');
    }
}