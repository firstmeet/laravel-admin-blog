<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkuSpecGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sku_spec_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name")->comment('属性组名称');
            $table->integer('sort')->comment('排序');
            $table->mediumInteger('status')->comment('状态 1:未启用 2:启用');
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
        Schema::dropIfExists('sku_spec_groups');
    }
}
