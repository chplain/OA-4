<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNoteTable extends Migration
{
    /**
     * Run the migrations.
     * 用户的日记本
     * @return void
     */
    public function up()
    {
        Schema::create('user_note', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->text('content')->comment('内容');
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
        Schema::drop('user_note');
    }
}
