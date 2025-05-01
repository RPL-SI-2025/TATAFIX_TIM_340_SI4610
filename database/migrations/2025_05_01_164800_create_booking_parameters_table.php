<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('booking_parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('parameter_id');
            $table->decimal('input_value', 12, 2);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->foreign('parameter_id')->references('id')->on('service_parameters')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_parameters');
    }
};
