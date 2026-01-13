<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fast_booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fast_booking_id')
                ->constrained('fast_bookings')
                ->onDelete('cascade');
            $table->string('tracking_no')->unique();
            $table->string('receiver_name');
            $table->text('address');
            $table->integer('pcs');
            $table->decimal('weight', 8, 2);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fast_booking_items');
    }
};
