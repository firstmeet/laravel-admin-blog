<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->comment('关联的商品id');
            $table->json('sku')->comment('sku');
            $table->float('active_price')->comment('拼团价格');
            $table->float('single_price')->comment('单独购买价格');
            $table->integer('sale')->comment('销量');
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
        Schema::dropIfExists('skus');
    }
}
