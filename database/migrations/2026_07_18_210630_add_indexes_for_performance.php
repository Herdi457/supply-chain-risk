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
        Schema::table('ports', function (Blueprint $table) {
            // Add index on country_code for faster joins and filtering
            $table->index('country_code', 'idx_ports_country_code');
            
            // Add composite index for coordinate searches
            $table->index(['latitude', 'longitude'], 'idx_ports_coordinates');
        });

        Schema::table('risk_scores', function (Blueprint $table) {
            // Index on country_id already exists (foreign key), but let's ensure updated_at is indexed
            $table->index('updated_at', 'idx_risk_scores_updated_at');
            
            // Index on risk_level for filtering
            $table->index('risk_level', 'idx_risk_scores_level');
            
            // Index on total_risk_score for sorting
            $table->index('total_risk_score', 'idx_risk_scores_total');
        });

        Schema::table('countries', function (Blueprint $table) {
            // Add index on code for faster lookups
            // Note: code already has unique constraint, which creates an index automatically
            // But let's ensure name is indexed for searching
            $table->index('name', 'idx_countries_name');
            $table->index('region', 'idx_countries_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->dropIndex('idx_ports_country_code');
            $table->dropIndex('idx_ports_coordinates');
        });

        Schema::table('risk_scores', function (Blueprint $table) {
            $table->dropIndex('idx_risk_scores_updated_at');
            $table->dropIndex('idx_risk_scores_level');
            $table->dropIndex('idx_risk_scores_total');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropIndex('idx_countries_name');
            $table->dropIndex('idx_countries_region');
        });
    }
};
