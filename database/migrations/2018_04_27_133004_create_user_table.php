<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('用户id');
            $table->char('nickname')->comment('用户昵称');
            $table->char('email',50)->unique()->comment('email');
            $table->char('password',50)->comment('用户密码');
            $table->char('avatar',50)->nullable()->comment('用户头像');
            $table->enum('sex',['man','women'])->comment('性别');
            $table->char('phone',11)->unique()->comment('用户手机号');
            $table->timestamp('birth')->comment('出生日期');
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
        Schema::drop('users');
    }
}
