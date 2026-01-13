<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelItemsTable extends Migration
{
    public function up()
    {
        Schema::create('parcel_items', function (Blueprint $table) {
            $table->id();
            $table->string('barcode');
            $table->unsignedBigInteger('hub_id'); // Foreign key to hubs table
            $table->unsignedBigInteger('parcel_id'); // Foreign key to parcels table
            $table->decimal('price', 8, 2);
            $table->timestamps();

            // $table->string('hub_name')->nullable()->after('hub_id');
            $table->string('hub_name')->nullable();
            $table->foreign('hub_id')->references('id')->on('hubs')->onDelete('cascade');
            $table->foreign('parcel_id')->references('id')->on('parcels')->onDelete('cascade');
        });
    }

    public function down()

    {
        Schema::dropIfExists('parcel_items');

    }
}
