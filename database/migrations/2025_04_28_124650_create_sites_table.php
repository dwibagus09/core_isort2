<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('sites', function (Blueprint $table) {
        $table->id('site_id');
        $table->string('site_name')->nullable();
        $table->string('site_fullname')->nullable();
        $table->string('initial')->nullable();
        $table->string('action_plan_color')->nullable();
        $table->integer('city_id')->nullable();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
