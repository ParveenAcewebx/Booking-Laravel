<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->longText('data');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->string('template_name', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_templates');
    }
};
