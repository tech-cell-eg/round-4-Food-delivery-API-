<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('chef_id')->constrained('chefs');
            $table->string('status')->default('active');
            $table->timestamps();
            
            // Ensure one conversation per customer-chef pair
            $table->unique(['customer_id', 'chef_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
