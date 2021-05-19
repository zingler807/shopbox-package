<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->nullable()->unique();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('shipping_rate_id')->nullable();
            $table->string('status')->default('draft');

            $table->float('subtotal', 8, 2)->default(0);
            $table->float('shipping', 8, 2)->default(0)->nullable();
            $table->float('discount_amount', 8, 2)->default(0)->nullable();
            $table->float('total', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('billing_first_name')->nullable();
            $table->string('billing_last_name')->nullable();
            $table->string('billing_address_line1')->nullable();
            $table->string('billing_address_line2')->nullable();
            $table->string('billing_address_city')->nullable();
            $table->string('billing_address_country')->nullable();
            $table->string('billing_address_postcode')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('tracking_code')->nullable();
            $table->string('stripe_payment_id')->nullable();
            $table->string('stripe_card_last_4')->nullable();
            $table->datetime('paid_at')->nullable();
            $table->datetime('refunded_at')->nullable();
            $table->string('discount_code')->nullable();
            $table->float('refund_amount', 8, 2)->default(0);
            $table->datetime('fullfilled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
