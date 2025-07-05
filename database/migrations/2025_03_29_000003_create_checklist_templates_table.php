<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->increments('template_id');
            $table->integer('site_id')->nullable(false);
            $table->integer('department_id')->nullable(false);
            $table->string('template_name', 255)->nullable(false);
            
            // Tambahkan indeks untuk kolom yang sering di-query
            $table->index('site_id');
            $table->index('department_id');
            
            // Jika ingin menambahkan timestamps
            $table->timestamps();
            
            // Jika ingin collation latin1_swedish_ci
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
        Schema::dropIfExists('checklist_templates');
    }
}