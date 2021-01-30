<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique();
            $table->foreignId('supplier_id')->default(0);
            $table->unsignedInteger('in_stock')->default(0);
            $table->unsignedInteger('bad_order_stock')->default(0);
            $table->unsignedInteger('stock_in')->default(0);
            $table->unsignedInteger('stock_out')->default(0);
            $table->unsignedInteger('minimum_reorder_level')->default(0);
            $table->unsignedInteger('incoming')->default(0);
            $table->unsignedDouble('default_purchase_costs', 10, 2)->default(0.00);
            $table->timestamps();

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
        Schema::dropIfExists('stocks');
    }
}
