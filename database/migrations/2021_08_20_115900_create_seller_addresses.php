<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerAddresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_no');
            $table->mediumText('address1');
            // $table->mediumText('address2')->nullable();
            $table->string('city');
            $table->mediumText('company_name')->nullable();
            $table->string('zip_code');
            $table->string('country');
            $table->string('state');
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
        Schema::dropIfExists('seller_addresses');
    }
}
