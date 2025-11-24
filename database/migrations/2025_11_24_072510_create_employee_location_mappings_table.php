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
        Schema::create('employee_location_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('employee_location');
            $table->unsignedBigInteger('adc_centre_id');
            $table->timestamps();
            $table->foreign('adc_centre_id')->references('id')->on('adc_centres')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_location_mappings');
    }
};
