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
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->string('port_name');        // Nama Pelabuhan (Contoh: Shanghai, Port of Tanjung Priok)
            $table->string('country_code', 3);  // Kode Negara 2-3 huruf (Contoh: CN, ID)
            
            // Kolom koordinat wajib menggunakan decimal khusus agar presisi di peta Leaflet.js
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            
            $table->string('index_number', 20)->nullable(); // Nomor referensi World Port Index
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};