<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan', function (Blueprint $table) {
            $table->increments('id');
            $table->varchar('title',45)->comment('标题');
            $table->text(`content`)->comment('内容');
            $table->integer(`status`)->comment('状态') ;
            $table->integer('creatorId')->unsigned()->comment('创建者id');
            $table->integer('executorId')->unsigned()->comment('执行用户id');
            $table->integer('userGroupId')->unsigned()->comment('用户组id');
            $table->foreign('executorId')->references('id')->on('users');
            $table->foreign('creatorId')->references('id')->on('users');
            $table->foreign('userGroupId')->references('id')->on('user_group');
            $table->timestamp('deadTime')->comment('截止日期');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('plan');
    }
}
