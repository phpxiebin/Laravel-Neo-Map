<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 活动信息表
        Schema::create('activity', function (Blueprint $table) {
            //必须字段
            $table->uuid('id');
            $table->primary('id');

            /********* STA 用户添加字段 STA ********/
            $table->uuid('organization_id')->default('')->comment('机构ID');
            $table->string('title')->default('')->comment('活动标题');
            $table->string('digest')->default('')->comment('活动摘要');
            $table->string('begin_date')->default('')->comment('开始时间');
            $table->string('end_date')->default('')->comment('结束时间');
            $table->string('address')->default('')->comment('活动地点');
            $table->string('province')->default('')->comment('所在省份');
            $table->string('city')->default('')->comment('所在城市');
            $table->string('district')->default('')->comment('所在区县');
            $table->integer('number_limitation')->default('0')->comment('活动限制人数 [0不限制]');
            $table->string('type')->default('')->comment('活动类型');
            $table->string('cover')->default('')->comment('封面');
            $table->mediumText('details')->comment('活动详情');
            $table->tinyInteger('status')->unsigned()->default(1)->comment('状态 [1-报名中, 2-已结束]');
            $table->tinyInteger('mark')->unsigned()->default(0)->comment('标记 [0-无, 1-热门]');
            $table->tinyInteger('display')->unsigned()->default(0)->comment('显示 [0-显示, 1-隐藏]');
            /********* END 用户添加字段 END ********/

            //必须字段
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
        Schema::dropIfExists('activity');
    }
}
