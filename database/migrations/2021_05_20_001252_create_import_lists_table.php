<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_lists', function (Blueprint $table) {
            $table->smallIncrements("imlId")->unsigned(false);
            $table->string("import_Id", 8);
            $table->smallInteger("storage_Id");
            $table->integer("order_qty");
            $table->integer("reciev_qty");
            $table->double("imlprice");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_lists');
    }
}
