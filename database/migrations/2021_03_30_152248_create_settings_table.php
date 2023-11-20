<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('phone_no_1');
            $table->string('phone_no_2')->nullable();
            $table->text('address');
            $table->text('facebook')->nullable();
            $table->text('tweeter')->nullable();
            $table->text('linkedIn')->nullable();
            $table->text('instagram')->nullable();
            $table->text('logo')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
