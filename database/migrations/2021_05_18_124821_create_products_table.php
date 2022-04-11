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
            $table->string('pdId',8)->primary()->unsigned(false);
            $table->smallInteger('brand_Id');
            $table->smallInteger('categories_Id');
            $table->smallInteger('colour_Id');
            $table->smallInteger('supplier_Id');
            $table->string('pdname', 100)->index();
            $table->string('pdfullname', 120);
            $table->double('pdcost');
            $table->double('pdrate');
            $table->double('pdprice');
            $table->string('pdprogression', 15);
            $table->binary('pdphotos_main');
            $table->binary('pdphotos_sub');
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
        Schema::dropIfExists('products');
    }
}
