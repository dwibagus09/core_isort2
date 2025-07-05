<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaizenTypeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('kaizen_type', function (Blueprint $table) {
        $table->id('kaizen_type_id');
        $table->string('kaizen_type')->nullable();
        $table->integer('department_id')->nullable();
        $table->integer('sort_order');
        $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        $table->timestamp('created_at')->default(DB::raw("'0000-00-00 00:00:00'"));
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaizen_type');
    }
};
