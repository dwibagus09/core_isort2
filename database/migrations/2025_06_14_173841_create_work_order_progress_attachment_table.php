<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrderProgressAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('work_order_progress_attachment', function (Blueprint $table) {
        $table->id('attachment_id');
        $table->integer('site_id')->nullable();
        $table->integer('department_id')->nullable();
        $table->integer('user_id')->nullable();
        $table->integer('wo_id')->nullable();
        $table->string('filename')->nullable();
        $table->dateTime('uploaded_date')->nullable();
        $table->text('description')->nullable();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_progress_attachment');
    }
};
