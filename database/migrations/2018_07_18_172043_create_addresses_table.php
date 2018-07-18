<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('address');
            $table->integer('province');
            $table->integer('city');
            $table->integer('county');
            $table->string('phone_number')->comment("联系方式");
            $table->string('consignee_name')->comment("收货人姓名");
            $table->tinyInteger("default")->default(0)->comment("默认地址");
            $table->tinyInteger("pick")->default(0)->comment("选中地址");
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
        Schema::dropIfExists('addresses');
    }
}
