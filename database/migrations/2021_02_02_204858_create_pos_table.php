<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos', function (Blueprint $table) {
            $table->id();
            $table->string('cashier');
            $table->foreignId('customer_id')->nullable();
            $table->char('status', 20)->default('Pending');
            $table->timestamps();

            $table->foreign('customer_id')
                    ->references('id')
                    ->on('customers')
                    ->nullOnDelete();
        });

        Schema::create('pos_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id');
            $table->foreignId('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedDouble('price', 20, 2)->default(0.00);
            $table->char('unit_of_measurement', 20)->default('pcs');
            $table->unsignedDouble('sub_total', 20, 2)->default(0.00);
            $table->unsignedDouble('discount', 20, 2)->default(0.00);
            $table->unsignedDouble('tax', 20, 2)->default(0.00);
            $table->unsignedDouble('total', 20, 2)->default(0.00);
            $table->timestamps();

            $table->unique([
                'pos_id',
                'product_id'
            ]);

            $table->foreign('pos_id')
                    ->references('id')
                    ->on('pos')
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
        Schema::dropIfExists('pos_details');
        Schema::dropIfExists('pos');
    }
}
