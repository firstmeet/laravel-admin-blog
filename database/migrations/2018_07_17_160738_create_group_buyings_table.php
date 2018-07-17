<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupBuyingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_buyings', function (Blueprint $table) {
            $table->increments('id');
            $table->string("group_id")->comment("团id");
            $table->integer("user_id")->comment("团长id");
            $table->integer("goods_id")->comment("拼团商品id");
            $table->integer("freight_id")->comment("物流模板id");
            $table->integer("group_size")->comment("团最大人数");
            $table->integer("current_size")->default(0)->comment("当前人数 支付后增加");
            $table->timestamp("exp_time")->comment("过期时间");
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
        Schema::dropIfExists('group_buyings');
    }
}
