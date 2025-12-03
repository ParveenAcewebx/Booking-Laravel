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
        Schema::table('vendors', function (Blueprint $table) {
            $table->boolean('stripe_mode')->nullable()->after('status');

            // Test keys
            $table->string('stripe_test_site_key')->nullable()->after('stripe_mode');
            $table->string('stripe_test_secret_key')->nullable()->after('stripe_test_site_key');

            // Live keys
            $table->string('stripe_live_site_key')->nullable()->after('stripe_test_secret_key');
            $table->string('stripe_live_secret_key')->nullable()->after('stripe_live_site_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_mode',
                'stripe_test_site_key',
                'stripe_test_secret_key',
                'stripe_live_site_key',
                'stripe_live_secret_key'
            ]);
        });
    }
};
