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
            $table->string('order_no');
            $table->integer('seller_id')->nullable();
            $table->integer('buyer_id')->nullable();
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('sub_total');
            $table->string('tax');
            $table->decimal('total_amount', 8, 2);
            $table->enum('order_status', ['', 'paid', 'pending', 'cancelled', 'unpaid', 'completed', 'shipped']);
            $table->tinyInteger('status')->default('1');
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
