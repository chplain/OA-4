<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_role_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('role')->unsigned()->comment('可以设置role');
            $table->integer('permissionId')->unsigned()->comment('permissionId');
            $table->foreign('permissionId')->references('id')->on('permission');
            $table->foreign('roleId')->references('id')->on('role');
            $table->foreign('role')->references('id')->on('role');
            $table->index(['roleId','permissionId','role'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_role_permission');
    }
}
