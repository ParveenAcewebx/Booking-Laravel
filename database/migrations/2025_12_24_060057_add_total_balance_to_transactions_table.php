<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('total_balance', 10, 2)
                ->after('payment_id');
        });
    }

    public function down()
    {   
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('total_balance');
        });
    }

};
