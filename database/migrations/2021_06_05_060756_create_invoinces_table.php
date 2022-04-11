<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoinces', function (Blueprint $table) {
            $table->string("invId",8)->primary()->unsigned(false);
            $table->smallInteger("customer_Id");
            $table->double("invQty");
            $table->double("invPrice",8);
            $table->string("invState",15);
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
        Schema::dropIfExists('invoinces');
    }
}
