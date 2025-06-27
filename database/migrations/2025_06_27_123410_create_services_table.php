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
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('category')->nullable();
            $table->string('thumbnail')->nullable();
            $table->longText('staff_member')->nullable(); // JSON or serialized data
            $table->integer('status')->default(0); // 1 = active, 0 = inactive
            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->longText('gallery')->nullable(); // JSON or serialized image URLs
            $table->integer('appointment_status')->default(0); // 1 = pending, 0 = approved
            $table->string('cancelling_days')->nullable(); // e.g. "3"
            $table->string('cancelling_numbers')->nullable(); // e.g. "2"
            $table->timestamps();
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
