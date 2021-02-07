<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockReceivedTable extends Migration
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
            $table->foreignId('purchase_order_id');
            $table->foreignId('supplier_id');
            $table->timestamps();

            $table->unique(['purchase_order_id', 'supplier_id']);

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
            $table->foreignId('received_stock_id');
            $table->foreignId('purchase_order_details_id');
            $table->foreignId('product_id');
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

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
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
        Schema::dropIfExists('stock_received');
        Schema::dropIfExists('stock_received_details');
    }
}
