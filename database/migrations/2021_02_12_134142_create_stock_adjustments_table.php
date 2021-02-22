<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjusted_by');
            $table->string('reason');
            $table->timestamps();
        });

        Schema::create('stock_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_adjustment_id');
            $table->foreignId('stock_id');
            $table->unsignedInteger('in_stock')->default(0);
            $table->unsignedInteger('added_stock')->default(0);
            $table->unsignedInteger('removed_stock')->default(0);
            $table->unsignedInteger('counted_stock')->default(0);
            $table->unsignedInteger('stock_after')->default(0);
            $table->timestamps();

            $table->foreign('stock_adjustment_id')
                ->references('id')
                ->on('stock_adjustments')
                ->onDelete('cascade');

            $table->foreign('stock_id')
                ->references('id')
                ->on('stocks')
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
        Schema::dropIfExists('stock_adjustment_details');
        Schema::dropIfExists('stock_adjustments');
    }
}
