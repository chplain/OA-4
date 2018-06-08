<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserUserGroupRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_user_group_role', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('userId')->unsigned()->comment('userGroupId');
            $table->integer('userGroupId')->unsigned()->comment('userGroupId');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->foreign('userGroupId')->references('id')->on('user_group');
            $table->foreign('roleId')->references('id')->on('role');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['roleId','userGroupId','userId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_user_group_role');
    }
}
