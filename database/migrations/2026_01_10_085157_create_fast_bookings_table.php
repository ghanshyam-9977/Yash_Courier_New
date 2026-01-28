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
        Schema::create('fast_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->unsignedBigInteger('from_branch_id');
            $table->unsignedBigInteger('to_branch_id');
<<<<<<< HEAD
            $table->string('network')->nullable();
=======
            $table->unsignedBigInteger('network_id')->nullable();
>>>>>>> 47c1f9dc9f4358a9976f1341ff7c3c2ae3e15850
            $table->enum('payment_type', ['CASH', 'ONLINE', 'COD', 'SLIP']);
            $table->string('slip_no')->nullable();
            $table->integer('total_pcs')->default(0);
            $table->decimal('total_weight', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('cod_amount', 10, 2)->default(0);
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fast_bookings');
    }
};
