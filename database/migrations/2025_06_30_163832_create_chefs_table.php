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
        Schema::create('chefs', function (Blueprint $table) {
            $table->foreignId('id')
                ->constrained('users')
                ->onDelete('cascade')
                ->primary(); //  مفتاح أساسي وأجنبي في نفس الوقت
            $table->string('speciality')->nullable();
            $table->integer('experience_years')->nullable();
            $table->string('national_id')->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('stripe_account_id')->nullable();
            $table->text('location')->nullable();
            $table->string('image')->nullable();
            $table->string('bio')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chefs');
    }
};
