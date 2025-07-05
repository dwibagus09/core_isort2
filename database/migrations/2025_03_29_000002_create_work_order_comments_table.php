<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrderCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_order_comments', function (Blueprint $table) {
            // Primary key
            $table->increments('comment_id');
            
            // Foreign keys and references
            $table->integer('site_id')->nullable(false);
            $table->integer('category_id')->nullable();
            $table->integer('wo_id')->nullable(false);
            $table->integer('user_id')->nullable(false);
            
            // Comment content
            $table->text('comment')->nullable(false);
            $table->datetime('comment_date')->nullable(false);
            
            // Status (enum: 0=reject, 1=approve)
            $table->tinyInteger('status')->nullable(false)->comment('0=reject, 1=approve');
            
            // Indexes
            $table->index('site_id');
            $table->index('wo_id');
            $table->index('user_id');
            $table->index('status');
            
            // Timestamps
            $table->timestamps();
            
            // Collation settings
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_order_comments');
    }
}