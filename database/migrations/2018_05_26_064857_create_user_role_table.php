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
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->integer('role_id')->unsigned()->comment('角色id');
            $table->timestamps();
            //联合索引
            $table->index(['user_id','role_id'])->unique()->comment('用户对于一个角色只能被授予一次');
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
