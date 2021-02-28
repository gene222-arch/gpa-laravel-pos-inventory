<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('sku', 16);
            $table->char('barcode', 13);
            $table->string('name')->unique();
            $table->string('image')->nullable()->default('no_image.svg');
            $table->string('category_id')->nullable();
            $table->char('sold_by', 13);
            $table->unsignedDouble('price', 10, 2);
            $table->unsignedDouble('cost', 10, 2);
            $table->timestamps();

            $table->unique(['sku', 'barcode']);

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
