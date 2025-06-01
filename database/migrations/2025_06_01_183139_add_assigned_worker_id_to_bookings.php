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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_worker_id')->nullable()->after('status_code');
            $table->foreign('assigned_worker_id')->references('id')->on('users');
            $table->timestamp('assigned_at')->nullable()->after('assigned_worker_id');
            $table->decimal('dp_amount', 10, 2)->nullable()->after('assigned_at');
            $table->decimal('final_amount', 10, 2)->nullable()->after('dp_amount');
            $table->timestamp('dp_paid_at')->nullable()->after('final_amount');
            $table->timestamp('final_paid_at')->nullable()->after('dp_paid_at');
            $table->timestamp('completed_at')->nullable()->after('final_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['assigned_worker_id']);
            $table->dropColumn([
                'assigned_worker_id',
                'assigned_at',
                'dp_amount',
                'final_amount',
                'dp_paid_at',
                'final_paid_at',
                'completed_at'
            ]);
        });
    }
};
