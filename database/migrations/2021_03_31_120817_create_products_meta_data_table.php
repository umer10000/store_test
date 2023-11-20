<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsMetaDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_meta_data', function (Blueprint $table) {
            $table->id();
           $table->integer('product_id');//->constrained()->cascadeOnDelete();
            $table->text('meta_tag_title');
            $table->text('meta_tag_description');
            $table->text('meta_tag_keywords');
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
        Schema::dropIfExists('products_meta_data');
    }
}
