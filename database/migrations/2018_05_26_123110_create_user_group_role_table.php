<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user_group_role', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('userGroupId')->unsigned()->comment('userGroupId');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->foreign('userGroupId')->references('id')->on('user_group');
            $table->foreign('roleId')->references('id')->on('role');
            $table->index(['roleId','userGroupId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user_group_role');
    }
}
