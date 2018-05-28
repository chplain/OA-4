<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_table', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->integer('userId')->unsigned()->comment('用户id');
            $table->integer('roleId')->unsigned()->comment('角色id');
            $table->timestamps();
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('roleId')->references('id')->on('role');
            //联合索引
            $table->index(['userId','roleId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_role_table');
    }
}
