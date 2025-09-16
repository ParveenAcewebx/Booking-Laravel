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
        Schema::table('email_templates', function (Blueprint $table) {
            if (Schema::hasColumn('email_templates', 'macro')) {
                $table->dropColumn('macro');
            }
            if (Schema::hasColumn('email_templates', 'dummy_template')) {
                $table->dropColumn('dummy_template');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->string('macro', 255)->nullable();
            $table->text('dummy_template')->nullable();
        });
    }
};
