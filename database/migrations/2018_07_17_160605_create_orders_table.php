<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id')->comment("订单号");
            $table->string("trade_no")->comment("支付流水号");
            $table->tinyInteger("type")->default(1)->comment("订单类型 1：拼团 2：单独购买");
            $table->decimal('payment_amount')->comment("支付金额")->default(0.00);
            $table->decimal('freight')->comment("运费")->default(0.00);
            $table->decimal("preferential_amount")->default(0.00)->comment("优惠金额");
            $table->integer('user_id')->comment("用户编号");
            $table->timestamp("pay_time")->comment("支付时间");
            $table->tinyInteger("is_pay")->default(0)->comment("是否支付 0：未支付 1：已支付");
            $table->tinyInteger("is_ship")->default(0)->comment("是否收货 0:未收货 1:已收货");
            $table->timestamp("ship_time")->comment("收货时间");
            $table->tinyInteger("is_receipt")->default(0)->comment("是否发货 0:未发货 1:已发货");
            $table->timestamp("receipt_time")->comment("发货时间");
            $table->string("ship_number",100)->comment("快递单号")->default(null);
            $table->tinyInteger("status")->default(1)->comment("订单状态 1：正常 0：禁用 -1：删除");
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
