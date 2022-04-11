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
            $table->smallIncrements('supId')->unsigned(false);
            $table->string('supgender', 10);
            $table->string('supfullname', 100)->index();
            $table->string('suptell', 15);
            $table->text('supaddress');
            $table->string('supemail', 150);
            $table->string('supstate', 15);
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
