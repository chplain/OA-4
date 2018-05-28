<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pan', function (Blueprint $table) {
            $table->increments('id');
            $table->char('filename',40)->comment('文件名');
            $table->char('locate',30)->comment('文件位置');
            $table->integer('userId')->unsigned()->comment('上传用户id');
            $table->foreign('userId')->references('id')->on('users');
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
        Schema::drop('pan');
    }
}
