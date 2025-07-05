<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('comments', function (Blueprint $table) {
          $table->id('comment_id');
          $table->integer('kaizen_id');
          $table->text('comment')->nullable();
          $table->dateTime('comment_date')->nullable();
          $table->integer('user_id');
          $table->integer('site_id')->nullable();
          $table->string('filename')->nullable();
          $table->boolean('isclosed')->default(0);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
