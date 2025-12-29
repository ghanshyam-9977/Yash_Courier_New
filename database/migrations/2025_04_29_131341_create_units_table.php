<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->enum('transport_type', ['By Road', 'By Air']);
            $table->decimal('weight', 8, 2); // in kg
            $table->decimal('weight_price', 8, 2); // price based on weight
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }

};
