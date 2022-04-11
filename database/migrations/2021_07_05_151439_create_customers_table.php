<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->smallIncrements("cusId")->unsigned(false);
            $table->string("cus_gender", 15);
            $table->string("cus_fullname", 100);
            $table->string("cus_phone", 15);
            $table->string("cus_provint", 50)->nullValue(false);
            $table->string("cus_distric", 100)->nullValue(false);
            $table->string("cus_home", 50)->nullValue(false);
            $table->text("cus_description")->nullValue(false);
            $table->string("email", 80)->unique();
            $table->string("password");
            $table->string("cus_state", 15);
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
        Schema::dropIfExists('customers');
    }
}
