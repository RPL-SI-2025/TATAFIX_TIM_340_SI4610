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
        Schema::create('booking_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status_code')->unique();
            $table->string('display_name');
            $table->string('color_code')->nullable();
            $table->boolean('requires_action')->default(false);
            $table->string('next_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_statuses');
    }
};