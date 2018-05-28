<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserGroupRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user_group_role_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('perId')->unsigned()->comment('permissionId');
            $table->integer('uGId')->unsigned()->comment('userGroupId');
            $table->foreign('uGId')->references('id')->on('user_group');
            $table->foreign('perId')->references('id')->on('permission');
            $table->foreign('roleId')->references('id')->on('role');
            $table->index(['roleId','perId','uGId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user_group_role_permission');
    }
}
