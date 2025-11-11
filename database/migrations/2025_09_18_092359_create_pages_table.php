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
        Schema::create('pages', function (Blueprint $table) {
            $table->id(); 
            $table->string('title');  
            $table->text('content'); 
            $table->string('slug')->unique();  
            $table->string('status');  
            $table->string('feature_image')->nullable(); 
            $table->unsignedBigInteger('created_by');  
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['created_by']); // Drop foreign key first
        });

        Schema::dropIfExists('pages');
    }
};