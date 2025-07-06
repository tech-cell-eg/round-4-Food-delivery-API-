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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'processing', 'out_for_delivery', 'delivered', 'cancelled'])->default('pending');

            // Payment fields
            $table->string('payment_status')->default('pending')->comment('pending, paid, failed, refunded');
            $table->string('payment_method')->nullable()->comment('stripe, cash, etc.');
            $table->string('transaction_id')->nullable()->comment('معرف المعاملة من بوابة الدفع');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('amount_paid', 10, 2)->default(0)->comment('المبلغ المدفوع');
            $table->timestamp('paid_at')->nullable()->comment('تاريخ ووقت الدفع');
            $table->text('payment_details')->nullable()->comment('تفاصيل الدفع (JSON)');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
