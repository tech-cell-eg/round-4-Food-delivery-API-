<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            
            // Polymorphic relationship for sender (customer or chef)
            $table->string('sender_type'); // 'customer' or 'chef'
            $table->unsignedBigInteger('sender_id');
            
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Index for better performance on queries
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
