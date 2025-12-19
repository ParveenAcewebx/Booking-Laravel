<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'refunded_amount')) {
                $table->decimal('refunded_amount', 10, 2)->default(0)->after('amount'); 
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'refunded_amount')) {
                $table->dropColumn('refunded_amount');
            }
        });
    }
};
