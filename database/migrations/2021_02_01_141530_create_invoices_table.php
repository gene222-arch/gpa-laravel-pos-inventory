<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('cashier');
            $table->foreignId('customer_id');
            $table->char('status', 25)->default('Payment in process');
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')
                    ->references('id')
                    ->on('customers')
                    ->onDelete('cascade');
        });

        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id');
            $table->foreignId('product_id');
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedDouble('price', 20, 2)->default(0.00);
            $table->char('unit_of_measurement', 20)->default('each');
            $table->unsignedDouble('sub_total', 20, 2)->default(0.00);
            $table->unsignedDouble('discount', 20, 2)->default(0.00);
            $table->unsignedDouble('tax', 20, 2)->default(0.00);
            $table->unsignedDouble('total', 20, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('invoice_id')
                    ->references('id')
                    ->on('invoices')
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
        Schema::dropIfExists('invoice_details');
        Schema::dropIfExists('invoices');
    }
}
