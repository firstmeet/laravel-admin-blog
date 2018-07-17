<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupBuyingSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_buying_subs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("group_id")->comment("团id");
            $table->string("stocks_name")->comment("规格名称");
            $table->integer("sku_id")->comment("规格id");
            $table->integer("province_id")->comment("省份id");
            $table->integer("city_id")->comment("城市id");
            $table->integer("county_id")->comment("区id");
            $table->string("address")->comment("详细地址")->default(null);
            $table->string("phone_number")->comment("手机号")->default(null);
            $table->string("consignee_name")->comment("收货人姓名")->default(null);
            $table->decimal("payment_amount")->comment("支付金额")->default(0.00);
            $table->tinyInteger("is_master")->comment("是否是团长 0:不是 1是")->default(0);
            $table->tinyInteger("is_pay")->comment("是否支付 0:否 1:是")->default(0);
            $table->timestamp("pay_time")->comment("支付时间");
            $table->tinyInteger("status")->comment("拼团状态 0:失败 1:成功 2:退款")->default(0);
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
        Schema::dropIfExists('group_buying_subs');
    }
}
