<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndValueToSpecGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sku_spec_groups', function (Blueprint $table) {
            $table->enum('type',[1,2,3])->comment("类型");
            $table->string("value")->comment("值");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sku_spec_groups', function (Blueprint $table) {
            //
        });
    }
}
