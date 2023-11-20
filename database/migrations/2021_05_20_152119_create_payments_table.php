<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('special_deal_product_id')->nullable();
            $table->bigInteger('featured_ad_id')->nullable();
            $table->bigInteger('seller_id')->nullable();
            $table->decimal('amount', 8, 2);
            $table->text('description')->nullable();
            $table->string('card_id')->nullable();
            $table->string('pay_method_name')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
