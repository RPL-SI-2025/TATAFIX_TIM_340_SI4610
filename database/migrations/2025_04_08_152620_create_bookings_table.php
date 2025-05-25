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
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('booking_status_id')->constrained('booking_statuses');
            $table->unsignedBigInteger('service_id');
            $table->string('nama_pemesan'); // Perubahan: Menambahkan kolom nama pemesan
            $table->text('alamat'); // Perubahan: Menambahkan kolom alamat
            $table->string('no_handphone'); // Perubahan: Menambahkan kolom nomor handphone
            $table->date('tanggal_booking'); // Perubahan: Menambahkan kolom tanggal booking
            $table->time('waktu_booking'); // Perubahan: Menambahkan kolom waktu booking
            $table->text('catatan_perbaikan'); // Perubahan: Menambahkan kolom catatan perbaikan
            $table->timestamps();

            $table->foreign('service_id')
                  ->references('service_id')
                  ->on('services')
                  ->onDelete('cascade');
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
