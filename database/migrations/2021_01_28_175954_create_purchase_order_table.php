<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id');
            $table->string('status')->default('Pending');
            $table->unsignedInteger('total_received_quantity')->default(0);
            $table->unsignedInteger('total_ordered_quantity')->default(0);
            $table->unsignedInteger('total_remaining_ordered_quantity')->default(0);
            $table->timestamp('purchase_order_date')->nullable();
            $table->timestamp('expected_delivery_date')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')
                    ->references('id')
                    ->on('suppliers')
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
        Schema::dropIfExists('purchase_order');
    }
}
