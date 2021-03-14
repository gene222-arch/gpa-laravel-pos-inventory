<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('contact', 15)->unique();
            $table->string('email')->unique();
            $table->string('phone', 15)->unique();
            $table->string('website')->nullable();
            $table->string('main_address');
            $table->string('optional_address')->nullable();
            $table->string('city');
            $table->char('zipcode', 5);
            $table->string('country');
            $table->string('province');
            $table->timestamps();

        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
