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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'level_id')) {
                $table->foreignId('level_id')
                    ->after('role')
                    ->nullable()
                    ->constrained('levels')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'adc_centre_id')) {
                $table->foreignId('adc_centre_id')
                    ->after('level_id')
                    ->nullable()
                    ->constrained('adc_centres')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'level_id')) {
                $table->dropConstrainedForeignId('level_id');
            }

            if (Schema::hasColumn('users', 'adc_centre_id')) {
                $table->dropConstrainedForeignId('adc_centre_id');
            }
        });
    }
};
