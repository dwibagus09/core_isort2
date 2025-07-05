<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_categories', function (Blueprint $table) {
            $table->increments('category_id');
            $table->integer('site_id')->nullable(false);
            $table->string('category_name', 255)->nullable(false);
            $table->integer('sort_order')->nullable();
            
            // Tambahkan indeks untuk kolom yang sering di-query
            $table->index('site_id');
            $table->index('sort_order');
            
            // Jika ingin menambahkan timestamps
            // $table->timestamps();
            
            // Jika ingin menambahkan soft deletes
            // $table->softDeletes();
            
            // Jika ingin collation latin1_swedish_ci
            // $table->charset = 'latin1';
            // $table->collation = 'latin1_swedish_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist_categories');
    }
}