<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id');
            $table->timestamps();

            $table->foreign('pos_id')
                    ->references('id')
                    ->on('pos')
                    ->onDelete('cascade');
        });

        Schema::create('sales_return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_return_id');
            $table->foreignId('pos_details_id');
            $table->foreignId('product_id');
            $table->string('defect');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedDouble('price', 20, 2)->default(0.00);
            $table->char('unit_of_measurement', 15)->default('pcs');
            $table->unsignedDouble('sub_total', 20, 2)->default(0.00);
            $table->unsignedDouble('discount', 20, 2)->default(0.00);
            $table->unsignedDouble('tax', 20, 2)->default(0.00);
            $table->unsignedDouble('total', 20, 2)->default(0.00);
            $table->timestamps();

            $table->unique([
                'pos_details_id',
                'product_id'
            ]);

            $table->foreign('pos_details_id')
                    ->references('id')
                    ->on('pos_details')
                    ->onDelete('cascade');

            $table->foreign('sales_return_id')
                ->references('id')
                ->on('sales_returns')
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
        Schema::dropIfExists('sales_returns');
        Schema::dropIfExists('sales_return_details');
    }
}
