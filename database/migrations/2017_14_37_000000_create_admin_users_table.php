<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //后台用户表
        Schema::create('admin_users', function (Blueprint $table) {
            //必须字段
            $table->uuid('id'); //uuid
            $table->primary('id'); //uuid设为主键

            $table->string('name')->unique();
            $table->string('password');
            $table->string('salt', 64)->default('')->comment('密码加盐');

            $table->rememberToken();
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
        Schema::dropIfExists('admin_users');
    }
}
