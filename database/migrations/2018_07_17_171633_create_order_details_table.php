<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string("order_id")->comment("关联的订单order_id");
            $table->string("goods_name")->comment("商品名称");
            $table->string("sku_id")->comment("规格id");
            $table->integer("goods_id")->comment("商品id");
            $table->string("sku_name")->comment("规格名称");
            $table->decimal("price")->comment("价格");
            $table->integer("number")->comment("数量");
            $table->tinyInteger("status")->comment("状态 1:正常 0:禁用 -1:删除");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
