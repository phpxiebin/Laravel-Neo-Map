<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 机构信息表
        Schema::create('organization', function (Blueprint $table) {
            //必须字段
            $table->uuid('id');
            $table->primary('id');

            /********* STA 用户添加字段 STA ********/
            $table->string('logo')->default('')->comment('机构logo');
            $table->string('name')->default('')->comment('机构名称');
            $table->string('register_date')->default('')->comment('成立日期');
            $table->string('location')->default('')->comment('所在地');
            $table->string('phone')->default('')->comment('联系电话');
            $table->string('province')->default('')->comment('所在省份');
            $table->string('city')->default('')->comment('所在城市');
            $table->string('district')->default('')->comment('所在区县');
            $table->string('address')->default('')->comment('详细地址');
            $table->string('amap_coordinate')->default('')->comment('高德经纬度坐标');
            $table->string('website')->default('')->comment('官方网站');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('状态');;  // 状态，0：下线不显示，1：上线显示
            $table->tinyInteger('activated')->unsigned()->default(0)->comment('激活状态');;  // 状态，0：未激活，1：已激活
            $table->string('sphere',10)->default('')->comment('专注领域');
            $table->string('avatar')->default('')->comment('负责人头像');
            $table->string('principal')->default('')->comment('机构负责人');
            $table->string('identity')->default('')->comment('机构负责人身份');
            $table->string('intro')->default('')->comment('机构简介');
            $table->string('public_offering')->default('')->comment('是否具有公募资质 0-否 1-是');
            $table->string('donation_certificate')->default('')->comment('公开募捐证书');
            $table->string('certificate')->default('')->comment('法人登记证书');
            $table->string('total_donation')->default('')->comment('募捐总善款');
            $table->string('total_times')->default('')->comment('捐款总人次');
            $table->string('mission')->default('')->comment('组织使命');
            $table->string('coverage')->default('')->comment('服务区域');
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
        Schema::dropIfExists('organization');
    }
}
