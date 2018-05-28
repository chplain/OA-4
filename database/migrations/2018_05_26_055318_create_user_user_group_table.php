<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *用户用户组
     * @return void
     */
    public function up()
    {
        Schema::create('user_user_group', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->integer('userId')->unsigned()->comment('用户id');
            $table->integer('userGroupId')->unsigned()->comment('用户组id');
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('userGroupId')->references('id')->on('user_group');
            $table->index(['userId','userGroupId'])->unique();
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
        Schema::drop('user_user_group');
    }
}
