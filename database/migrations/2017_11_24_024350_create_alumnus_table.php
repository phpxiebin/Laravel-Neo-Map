<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 校友信息表
        Schema::create('alumnus', function (Blueprint $table) {
            //必须字段
            $table->uuid('id');
            $table->primary('id');

            /********* STA 用户添加字段 STA ********/
            $table->uuid('organization_id')->default('')->comment('机构ID');
            $table->string('name', 32)->default('')->comment('姓名');
            $table->string('identity', 32)->default('')->comment('身份');
            $table->string('class', 32)->default('')->comment('班级');
            $table->string('stud_no', 64)->default('')->comment('学号');
            $table->string('phone', 32)->default('')->comment('手机');
            $table->string('password', 64)->default('')->comment('密码');
            $table->string('salt', 64)->default('')->comment('密码加盐');
            $table->rememberToken();
            $table->tinyInteger('status')->unsigned()->default(0)->comment('状态 [0-未激活 1-已激活]');
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
        Schema::dropIfExists('alumnus');
    }
}
