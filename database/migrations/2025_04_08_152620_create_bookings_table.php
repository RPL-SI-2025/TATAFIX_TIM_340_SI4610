<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
            $table->string('nama_pemesan');
            $table->string('service_name');
            $table->date('tanggal_booking');
            $table->foreignId('status_id')->constrained('booking_statuses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};