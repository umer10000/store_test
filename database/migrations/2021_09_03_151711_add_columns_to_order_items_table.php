<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('is_archive')->nullable();
            $table->date('deleted_at')->nullable();
            $table->decimal('service_charges', 8, 2)->default(0);
            $table->string('tracking_number')->nullable();
            $table->string('shipping_doc_name')->nullable();
            $table->decimal('shipping_cost')->nullable();
            $table->text('shipping_name')->nullable();
            $table->string('packaging_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
        });
    }
}
