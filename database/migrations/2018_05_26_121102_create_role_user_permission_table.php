<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_role_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('permissionId')->unsigned()->comment('permissionId');
            $table->integer('userId')->unsigned()->comment('userId');
            $table->foreign('permissionId')->unsigned()->references('id')->on('permission');
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
        Schema::drop('user_role_permission');
    }
}
