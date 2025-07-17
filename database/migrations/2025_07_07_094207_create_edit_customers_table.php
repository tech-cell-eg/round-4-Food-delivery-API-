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
        Schema::table('customers', function (Blueprint $table) {

            $table->string('first_name', 45)->nullable();
            $table->string('last_name', 45)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('phone', 45)->nullable();
            $table->string('bio', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
