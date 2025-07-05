<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_order', function (Blueprint $table) {
            // Primary key
            $table->increments('wo_id');
            
            // Foreign keys and references
            $table->integer('site_id')->nullable(false);
            $table->integer('department_id')->nullable();
            $table->integer('kaizen_id')->nullable(false);
            
            // Scheduling information
            $table->datetime('start_scheduled_date')->nullable(false);
            $table->datetime('end_scheduled_date')->nullable(false);
            $table->integer('expected_work_time')->nullable(false);
            $table->tinyInteger('expected_work_time2')->nullable(false)->comment('0=min, 1=hour, 2=day');
            
            // Worker details
            $table->string('worker', 255)->nullable(false);
            $table->text('assigned_remark')->nullable(false);
            $table->datetime('assigned_date')->nullable(false);
            
            // Execution tracking
            $table->datetime('executed_date')->nullable(false);
            $table->datetime('finish_date')->nullable(false);
            
            // Approval status
            $table->integer('approved')->nullable(false);
            $table->datetime('approved_date')->nullable();
            
            // Indexes
            $table->index('site_id');
            $table->index('department_id');
            $table->index('kaizen_id');
            $table->index('approved');
            
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
        Schema::dropIfExists('work_order');
    }
}