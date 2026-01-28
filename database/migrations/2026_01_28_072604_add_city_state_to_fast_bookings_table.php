<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('fast_bookings', function (Blueprint $table) {
            $table->string('city')->nullable()->after('to_branch_id');
            $table->string('state')->nullable()->after('city');
            $table->string('network')->nullable()->after('state');
        });
    }

    public function down()
    {
        Schema::table('fast_bookings', function (Blueprint $table) {
            $table->dropColumn(['city', 'state','network']);
        });
    }
};
