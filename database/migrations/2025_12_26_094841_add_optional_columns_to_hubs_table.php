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
        Schema::table('hubs', function (Blueprint $table) {

            if (!Schema::hasColumn('hubs', 'item_type')) {
                $table->string('item_type')->nullable();
            }

            if (!Schema::hasColumn('hubs', 'transport_type')) {
                $table->string('transport_type')->nullable();
            }

            if (!Schema::hasColumn('hubs', 'unit')) {
                $table->string('unit', 100)->nullable();
            }

            if (!Schema::hasColumn('hubs', 'description')) {
                $table->text('description')->nullable();
            }

            if (!Schema::hasColumn('hubs', 'cgst')) {
                $table->decimal('cgst', 5, 2)->nullable();
            }

            if (!Schema::hasColumn('hubs', 'sgst')) {
                $table->decimal('sgst', 5, 2)->nullable();
            }

            if (!Schema::hasColumn('hubs', 'rate')) {
                $table->decimal('rate', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('hubs', 'quantity')) {
                $table->integer('quantity')->nullable();
            }

            if (!Schema::hasColumn('hubs', 'state')) {
                $table->string('state')->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('hubs', function (Blueprint $table) {

            if (Schema::hasColumn('hubs', 'item_type')) {
                $table->dropColumn('item_type');
            }

            if (Schema::hasColumn('hubs', 'transport_type')) {
                $table->dropColumn('transport_type');
            }

            if (Schema::hasColumn('hubs', 'unit')) {
                $table->dropColumn('unit');
            }

            if (Schema::hasColumn('hubs', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('hubs', 'cgst')) {
                $table->dropColumn('cgst');
            }

            if (Schema::hasColumn('hubs', 'sgst')) {
                $table->dropColumn('sgst');
            }

            if (Schema::hasColumn('hubs', 'rate')) {
                $table->dropColumn('rate');
            }

            if (Schema::hasColumn('hubs', 'quantity')) {
                $table->dropColumn('quantity');
            }

            if (Schema::hasColumn('hubs', 'state')) {
                $table->dropColumn('state');
            }

        });
    }
};
