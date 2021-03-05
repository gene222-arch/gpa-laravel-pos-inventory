<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id');
            $table->foreignId('customer_id');
            $table->foreignId('pos_id');
            $table->string('payment_type');
            $table->timestamps();
        });

        Schema::create('sales_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id');
            $table->foreignId('pos_details_id');
            $table->foreignId('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedDouble('price', 20, 2)->default(0.00);
            $table->char('unit_of_measurement', 20)->default('each');
            $table->unsignedDouble('sub_total', 20, 2)->default(0.00);
            $table->unsignedDouble('discount', 20, 2)->default(0.00);
            $table->unsignedDouble('tax', 20, 2)->default(0.00);
            $table->unsignedDouble('total', 20, 2)->default(0.00);
            
            $table->timestamps();

            $table->foreign('sales_id')
                ->references('id')
                ->on('sales')
                ->onDelete('cascade');

        
            $table->foreign('pos_details_id')
                ->references('id')
                ->on('pos_details')
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
        Schema::dropIfExists('sales_details');
        Schema::dropIfExists('sales');
    }
}
