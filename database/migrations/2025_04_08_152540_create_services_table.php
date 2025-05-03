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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('title_service');
            $table->text('description');
            $table->unsignedBigInteger('category_id');
            $table->decimal('base_price', 12, 2);
            $table->string('label_unit');
            $table->boolean('availbility')->default(true); // Note: there's a typo here, should be "availability"
            $table->float('rating_avg')->nullable();
            $table->timestamps();
            
            // Add foreign key constraints after defining all columns
            $table->foreign('provider_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
