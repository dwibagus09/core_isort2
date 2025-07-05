<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_items', function (Blueprint $table) {
            // Primary key
            $table->bigIncrements('item_id');
            
            // Foreign keys and required fields
            $table->integer('site_id')->nullable(false);
            $table->integer('checklist_id')->nullable(false);
            $table->integer('template_id')->nullable(false);
            $table->integer('template_item_id')->nullable(false);
            
            // Item details
            $table->string('item_name', 255)->nullable(false);
            
            // Condition tracking (first round)
            $table->integer('condition_staff')->nullable();
            $table->integer('condition_spv')->nullable();
            $table->datetime('save_date_staff')->nullable();
            $table->datetime('save_date_spv')->nullable();
            $table->integer('user_staff')->nullable();
            $table->integer('user_spv')->nullable();
            
            // Condition tracking (second round)
            $table->integer('condition_staff2')->nullable();
            $table->integer('condition_spv2')->nullable();
            $table->datetime('save_date_staff2')->nullable();
            $table->datetime('save_date_spv2')->nullable();
            $table->integer('user_staff2')->nullable();
            $table->integer('user_spv2')->nullable();
            
            // Condition tracking (third round)
            $table->integer('condition_staff3')->nullable();
            $table->integer('condition_spv3')->nullable();
            $table->datetime('save_date_staff3')->nullable();
            $table->datetime('save_date_spv3')->nullable();
            $table->integer('user_staff3')->nullable();
            $table->integer('user_spv3')->nullable();
            
            // HOD information
            $table->string('hod_image_update', 255)->nullable();
            $table->datetime('hod_image_update_date')->nullable();
            
            // Issue tracking
            $table->integer('issue_id')->nullable();
            
            // Additional fields
            $table->datetime('last_updated')->nullable();
            $table->text('remarks')->nullable();
            
            // Indexes
            $table->index('site_id');
            $table->index('checklist_id');
            $table->index('template_id');
            $table->index('template_item_id');
            $table->index('issue_id');
            
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
        Schema::dropIfExists('checklist_items');
    }
}