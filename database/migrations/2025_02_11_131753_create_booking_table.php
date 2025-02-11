<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_form_id');
            $table->unsignedBigInteger('customer_id');
            $table->dateTime('booking_datetime');
            $table->longText('booking_data');
            $table->string('service');
            $table->string('selected_staff')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            // Add foreign key constraints if necessary
            // $table->foreign('booking_form_id')->references('id')->on('booking_forms')->onDelete('cascade');
            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
