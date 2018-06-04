<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creatorId')->unsigned()->comment('发布消息的人');
            $table->text('content')->comment('消息内容');
            $table->integer('toUserId')->unsigned()->comment('接受消息的人');
            $table->foreign('creatorId')->references('id')->on('users')->comment('外键关联');
            $table->foreign('toUserId')->references('id')->on('users')->comment('外键关联');
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
        Schema::dropIfExists('info');
    }
}
