<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user_role_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('userId')->unsigned()->comment('userId');
            $table->integer('permissionId')->unsigned()->comment('permissionId');
            $table->foreign('permissionId')->references('id')->on('permission');
            $table->foreign('roleId')->references('id')->on('role');
            $table->foreign('userId')->references('id')->on('users');
            $table->index(['roleId','permissionId','userId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user_role_permission');
    }
}
