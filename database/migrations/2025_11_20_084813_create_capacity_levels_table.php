<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('capacity_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adc_date_id')->constrained('adc_dates')->cascadeOnDelete();
            $table->string('level');
            $table->integer('capacity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacity_levels');
    }
};
