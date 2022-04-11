<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->smallIncrements('storeId');
            $table->string('store_name',50);
            $table->string('store_phone',15);
            $table->string('store_email',50);
            $table->string('store_website',30);
            $table->text('store_address');
            $table->binary('store_image');
            $table->string('store_state',15);
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
        Schema::dropIfExists('stores');
    }
}
