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
        $defaultImg = 'http://127.0.0.1:8000/storage/images/Products/product_default_img.svg';

        Schema::create('products', function (Blueprint $table) use($defaultImg) {
            $table->id();
            $table->char('sku', 16);
            $table->char('barcode', 13);
            $table->string('name');
            $table->string('image')->nullable()->default( $defaultImg);
            $table->string('category')->index();
            $table->char('sold_by', 13);
            $table->unsignedDouble('price', 10, 2);
            $table->unsignedDouble('cost', 10, 2);
            $table->boolean('is_for_sale')->default(false);
            $table->timestamps();

            $table->unique(['sku', 'barcode', 'name']);
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
