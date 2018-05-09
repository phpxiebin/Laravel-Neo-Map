<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 全国行政区划表
        Schema::create('area', function (Blueprint $table) {
            //必须字段
            $table->uuid('id'); //uuid
            $table->primary('id'); //uuid设为主键

            /********* STA 添加字段 STA ********/
            $table->string('code', 6)->default('')->comment('地区编码');
            $table->string('lable', 20)->default('')->comment('地区名称');
            $table->uuid('parent_id')->default('')->comment('父级Id');
            $table->integer('level')->nullable()->comment('地区等级');
            /********* END 添加字段 END ********/

            //必须字段
            $table->integer('created_at')->unsigned()->nullable(); // 创建时间
            $table->integer('updated_at')->unsigned()->nullable(); // 更新时间
            $table->integer('deleted_at')->unsigned()->nullable(); // 软删除时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('area');
    }
}
