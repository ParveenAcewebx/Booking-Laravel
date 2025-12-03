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
        Schema::table('pages', function (Blueprint $table) {
            // Adding columns after `created_by`
            $table->string('meta_title')->nullable()->after('created_by');        
            $table->text('meta_keywords')->nullable()->after('meta_title');        
            $table->text('meta_description')->nullable()->after('meta_keywords');     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Drop the added columns in reverse order
            $table->dropColumn(['meta_title', 'meta_keywords', 'meta_description']);
        });
    }
};