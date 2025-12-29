<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('branch_payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('from_branch_id'); // Changed to store text value
            $table->string('to_branch_id');
            $table->enum('transport_type', ['by_road', 'by_air']);
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->timestamps();

            // Add foreign keys if needed
            // $table->foreign('from_branch_id')->references('id')->on('branches');
            // $table->foreign('to_branch_id')->references('id')->on('branches');
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_payment_requests');
    }
}
