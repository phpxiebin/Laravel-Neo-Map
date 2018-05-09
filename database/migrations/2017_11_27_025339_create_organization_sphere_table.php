<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationSphereTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 机构专注领域表
        Schema::create('organization_sphere', function (Blueprint $table) {
            //必须字段
            $table->uuid('id');
            $table->primary('id');

            /********* STA 用户添加字段 STA ********/
            $table->uuid('organization_id')->default('')->comment('机构ID');
            $table->string('sphere')->default('')->comment('领域名称');
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
        Schema::dropIfExists('organization_sphere');
    }
}
