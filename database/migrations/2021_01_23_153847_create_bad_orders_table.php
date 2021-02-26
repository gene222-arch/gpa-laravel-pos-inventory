<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBadOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bad_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id');
            $table->char('status', 20)->default('Pending');
            $table->timestamps();

            $table->foreign('purchase_order_id')
                ->references('id')
                ->on('purchase_order')
                ->onDelete('cascade');
        });

        Schema::create('bad_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bad_order_id');
            $table->foreignId('purchase_order_details_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->string('defect');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedDouble('price', 20, 2)->default(0.00);
            $table->char('unit_of_measurement', 15)->default('pcs');
            $table->unsignedDouble('amount', 20, 2)->default(0.00);
            $table->timestamps();

            $table->unique([
                'purchase_order_details_id',
                'product_id'
            ]);

            $table->foreign('bad_order_id')
                ->references('id')
                ->on('bad_orders')
                ->onDelete('cascade');

            $table->foreign('purchase_order_details_id')
                ->references('id')
                ->on('purchase_order_details')
                ->onDelete('NULL');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('NULL');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bad_orders');
        Schema::dropIfExists('bad_order_details');
    }
}
