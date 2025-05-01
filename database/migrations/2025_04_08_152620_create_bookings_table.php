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
            $table->id('booking_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('status_id');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('cascade');
            $table->foreign('status_id')->references('status_id')->on('booking_statuses')->onDelete('cascade');
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