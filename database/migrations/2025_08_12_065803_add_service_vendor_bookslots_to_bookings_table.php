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
            $table->unsignedBigInteger('vendor_id')->nullable()->after('service_id');
            $table->json('bookslots')->nullable()->after('vendor_id');

            // Optional: if you have related tables and want FK constraints
            // $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            // $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign keys first if you added them
            // $table->dropForeign(['service_id']);
            // $table->dropForeign(['vendor_id']);

            $table->dropColumn(['vendor_id','bookslots']);
        });
    }
};
