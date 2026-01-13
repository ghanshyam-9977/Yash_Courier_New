<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parcel_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parcel_id')->constrained()->onDelete('cascade');

            // ✅ Add hub_id as a foreignId first
            $table->foreignId('hub_id')->constrained('hubs')->onDelete('cascade');

            $table->string('barcode');
            $table->string('price', 10); // `10, 2` is incorrect for `string` type
            $table->string('hub_name')->nullable(); // ✅ removed `after('hub_id')`

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parcel_items');
    }
};


