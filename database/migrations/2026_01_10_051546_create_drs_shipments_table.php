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
        Schema::create('drs_shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drs_entry_id');
            $table->string('tracking_no');
            $table->string('booking_station');
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('pcs');
            $table->string('receiver_name');
            $table->text('address');
            $table->boolean('is_tracking_detail')->default(false);
            $table->timestamps();

            $table->foreign('drs_entry_id')->references('id')->on('drs_entries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drs_shipments');
    }
};
