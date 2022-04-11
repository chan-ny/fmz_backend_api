<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_details', function (Blueprint $table) {
            $table->smallIncrements("sdId")->unsigned(false);
            $table->string("invoince_Id", 8);
            $table->smallInteger("storage_Id");
            $table->string("product_Id", 8);
            $table->smallInteger("sdQty");
            $table->double("sdPrice", 8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sell_details');
    }
}
