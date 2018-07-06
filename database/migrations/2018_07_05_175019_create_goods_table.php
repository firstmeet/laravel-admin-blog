<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string("goods_name")->comment("商品名称");
            $table->integer("stock_number")->comment("库存数量");
            $table->float('active_price')->comment("拼团价格");
            $table->float('single_price')->comment("单独购买价格");
            $table->integer('active_man_number')->comment('拼团人数');
            $table->timestamp('start_time')->comment("开始时间");
            $table->timestamp('end_time')->comment("结束时间");
            $table->integer('active_valid_hours')->comment("有效时间");
            $table->integer('can_active_number')->comment("可开团数量");
            $table->string("description")->comment("描述");
            $table->string("picture")->comment("封面图");
            $table->json("pictures")->comment("轮播图");
            $table->longText("content")->comment("详细内容");
            $table->integer("sort")->default(0);
            $table->mediumInteger("status")->default(0)->comment("状态 0:未启用,1:启用");
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
        Schema::dropIfExists('goods');
    }
}
