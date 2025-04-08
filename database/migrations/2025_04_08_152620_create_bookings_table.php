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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemesan'); // Perubahan: Menambahkan kolom nama pemesan
            $table->text('alamat'); // Perubahan: Menambahkan kolom alamat
            $table->string('no_handphone'); // Perubahan: Menambahkan kolom nomor handphone
            $table->text('catatan_perbaikan'); // Perubahan: Menambahkan kolom catatan perbaikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};