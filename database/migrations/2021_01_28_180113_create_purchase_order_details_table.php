<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id');
            $table->foreignId('product_id');
            $table->unsignedInteger('received_quantity')->default(0);
            $table->unsignedInteger('ordered_quantity')->default(1);
            $table->unsignedInteger('remaining_ordered_quantity')->default(1);
            $table->unsignedDouble('purchase_cost', 10, 2)->default(0.00);
            $table->unsignedDouble('amount', 10, 2)->default(0.00);

            $table->timestamps();

            $table->unique(['purchase_order_id', 'product_id']);

            $table->foreign('purchase_order_id')
                    ->references('id')
                    ->on('purchase_order')
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
        Schema::dropIfExists('purchase_order_details');
    }
}
