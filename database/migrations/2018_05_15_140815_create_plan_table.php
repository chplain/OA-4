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
            $table->integer('creatorId')->unsigned()->comment('创建者id');
            $table->integer('executorId')->unsigned()->comment('执行用户id');
            $table->integer('userGroupId')->unsigned()->comment('用户组id');
            $table->foreign('executorId')->references('id')->on('users');
            $table->foreign('creatorId')->references('id')->on('users');
            $table->foreign('userGroupId')->references('id')->on('user_group');
            $table->time('deadTime')->comment('截止日期');
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
