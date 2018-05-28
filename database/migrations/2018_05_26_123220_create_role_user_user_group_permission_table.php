<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserUserGroupPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user_user_group_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('uUGId')->unsigned()->comment('userUserGroupId');
            $table->integer('perId')->unsigned()->comment('permissionId');
            $table->foreign('perId')->references('id')->on('permission');
            $table->foreign('roleId')->references('id')->on('role');
            $table->foreign('uUGId')->references('id')->on('user_user_group');
            $table->index(['roleId','perId','uUGId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user_user_group_permission');
    }
}
