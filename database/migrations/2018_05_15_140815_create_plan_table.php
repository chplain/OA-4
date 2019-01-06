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
            $table->char('title',45)->comment('标题');
            $table->text('content')->comment('内容');
            $table->integer('status')->comment('状态') ;
            $table->integer('creator_id')->unsigned()->comment('创建者id');
            $table->integer('executor_id')->unsigned()->comment('执行用户id');
            $table->integer('userGroup_id')->unsigned()->comment('用户组id');
            $table->timestamp('dead_at')->comment('截止日期');
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
