<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("stocks")->comment("关联规格id");
            $table->integer('number')->comment('规格商品数量');
            $table->float('active_price')->comment("拼团价格");
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
        Schema::dropIfExists('good_stocks');
    }
}
