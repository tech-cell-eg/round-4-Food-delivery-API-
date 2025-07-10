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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->decimal('lat', 10, 7);
            $table->decimal('lon', 10, 7);
            $table->string('class')->nullable();
            $table->string('type')->nullable();
            $table->string('place_rank')->nullable();
            $table->string('name')->nullable();
            $table->string('importance')->nullable();
            $table->string('display_name')->nullable();
            $table->string('address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
