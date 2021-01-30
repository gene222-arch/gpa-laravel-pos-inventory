<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_id');
            $table->foreignId('invoice_details_id');
            $table->foreignId('product_id');
            $table->string('defect');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedDouble('price', 20, 2)->default(0.00);
            $table->unsignedDouble('amount', 20, 2)->default(0.00);
            $table->char('unit_of_measurement', 15)->default('pcs');
            $table->timestamps();

            $table->unique([
                'invoice_details_id',
                'product_id'
            ]);

            $table->foreign('sales_return_id')
                ->references('id')
                ->on('sales_returns')
                ->onDelete('cascade');

            $table->foreign('invoice_details_id')
                ->references('id')
                ->on('invoice_details')
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
        Schema::dropIfExists('sales_return_details');
    }
}
