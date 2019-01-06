<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_pan', function (Blueprint $table) {
            $table->increments('id')->comment('文件编号');
            $table->integer('user_id')->unsigned()->comment('所有者的id');
            $table->char('filename',20)->comment('文件名');
            $table->char('locate',40)->comment('文件位置');
            $table->time('deleted_at')->comment('删除时间');
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
        Schema::drop('user_pan');
    }
}
