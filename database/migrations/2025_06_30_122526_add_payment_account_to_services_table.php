<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('payment_account')->nullable()->after('payment_mode'); // adjust 'after' as needed
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('payment_account');
        });
    }
};
