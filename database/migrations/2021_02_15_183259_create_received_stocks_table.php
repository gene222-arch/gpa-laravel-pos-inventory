<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivedStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('received_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->index();
            $table->foreignId('supplier_id')->index();
            $table->timestamps();

            $table->foreign('purchase_order_id')
                  ->references('id')
                  ->on('purchase_order')
                  ->onDelete('cascade');

            $table->foreign('supplier_id')
                  ->references('id')
                  ->on('suppliers')
                  ->onDelete('cascade');
        });

        Schema::create('received_stock_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('received_stock_id')->index();
            $table->foreignId('purchase_order_details_id')->index();
            $table->foreignId('product_id')->nullable();
            $table->unsignedInteger('received_quantity');
            $table->timestamps();

            $table->foreign('received_stock_id')
                  ->references('id')
                  ->on('received_stocks')
                  ->onDelete('cascade');

            $table->foreign('purchase_order_details_id')
                  ->references('id')
                  ->on('purchase_order_details')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('received_stock_details');
        Schema::dropIfExists('received_stocks');
    }
}
