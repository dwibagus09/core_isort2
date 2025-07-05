<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaizenProgressImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kaizen_progress_images', function (Blueprint $table) {
            $table->increments('kaizen_progress_image_id');
            $table->integer('kaizen_id')->nullable();
            $table->string('filename', 255)->nullable();
            $table->integer('user_id')->nullable();
            $table->datetime('upload_date')->nullable();
            
            // Foreign key constraint (optional)
            // $table->foreign('kaizen_id')->references('kaizen_id')->on('kaizen');
            // $table->foreign('user_id')->references('id')->on('users');
            
            // Jika ingin menambahkan timestamps
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
        Schema::dropIfExists('kaizen_progress_images');
    }
}