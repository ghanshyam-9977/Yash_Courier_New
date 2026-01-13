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
        Schema::create('drs_entries', function (Blueprint $table) {
            $table->id();
            $table->string('drs_no')->unique();
            $table->string('area_name');
            $table->date('drs_date');
            $table->time('drs_time');
            $table->unsignedBigInteger('delivery_boy_id');
            $table->string('pincode');
            $table->integer('total_shipments')->default(0);
            $table->timestamps();

            $table->foreign('delivery_boy_id')->references('id')->on('delivery_mans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drs_entries');
    }
};
