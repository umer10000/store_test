<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id');
            $table->integer('sub_category_id')->nullable();
            $table->enum('product_condition', ['used', 'new'])->nullable();
            $table->enum('product_type', ['Physical', 'Downloadable'])->nullable();
            $table->text('product_name');
            $table->text('description');
            $table->decimal('product_current_price',8,2);
            $table->decimal('discount_price',8,2)->default(0);
            $table->text('product_image');
            $table->integer('location_id');
            $table->decimal('shipping_charges')->nullable();
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
        Schema::dropIfExists('products');
    }
}
