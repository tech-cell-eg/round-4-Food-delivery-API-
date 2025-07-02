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
        Schema::table('orders', function (Blueprint $table) {
            // إضافة أعمدة الدفع
            $table->string('payment_status')
                ->default('pending')
                ->comment('pending, paid, failed, refunded');

            $table->string('payment_method')
                ->nullable()
                ->comment('stripe, cash, etc.');

            $table->string('transaction_id')
                ->nullable()
                ->comment('معرف المعاملة من بوابة الدفع');

            $table->decimal('amount_paid', 10, 2)
                ->default(0)
                ->comment('المبلغ المدفوع');

            $table->timestamp('paid_at')
                ->nullable()
                ->comment('تاريخ ووقت الدفع');

            $table->text('payment_details')
                ->nullable()
                ->comment('تفاصيل الدفع (JSON)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'transaction_id',
                'amount_paid',
                'paid_at',
                'payment_details'
            ]);
        });
    }
};
