<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_id')->unique()->nullable();
            $table->string('cashier');
            $table->char('payment_method', 20)->default('cash');
            $table->unsignedDouble('sub_total', 20, 2)->default(0.00);
            $table->unsignedDouble('discount', 20, 2)->default(0.00);
            $table->unsignedDouble('tax', 20, 2)->default(0.00);
            $table->unsignedDouble('shipping_fee', 20, 2)->default(0.00);
            $table->unsignedDouble('total', 20, 2);
            $table->unsignedDouble('cash', 20, 2)->default(0.00);
            $table->unsignedDouble('change', 20, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('pos_id')
                    ->references('id')
                    ->on('pos')
                    ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_payments');
    }
}
