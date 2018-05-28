<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserGroupPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user_group_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('userGroupId')->unsigned()->comment('userGroupId');
            $table->integer('permissionId')->unsigned()->comment('permissionId');
            $table->foreign('userGroupId')->references('id')->on('user_group');
            $table->foreign('permissionId')->references('id')->on('permission');
            $table->foreign('roleId')->references('id')->on('role');
            $table->index(['roleId','permissionId','userGroupId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user_group_permission');
    }
}
