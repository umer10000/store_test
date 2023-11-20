<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturedAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured_ads', function (Blueprint $table) {
            $table->id();
            $table->integer('featured_package_id')->nullable();
            // $table->integer('product_id');
            $table->integer('seller_id')->nullable();
            $table->string('banner');
            $table->decimal('amount', 8, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status')->default(1);
            $table->string('is_approve')->nullable();
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
        Schema::dropIfExists('featured_ads');
    }
}
