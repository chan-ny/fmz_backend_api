<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->smallIncrements("pmId")->unsigned(false);
            $table->string("invoince_Id", 8);
            $table->smallInteger("bank_Id");
            $table->binary("pmImage");
            $table->string("pmNumbersix",8);
            $table->double("pmPrice", 8);
            $table->string("pmState", 15);
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
        Schema::dropIfExists('payments');
    }
}
