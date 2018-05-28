<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePlanPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_plan_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('permissionId')->unsigned()->comment('permissionId');
            $table->integer('planId')->unsigned()->comment('planId');
            $table->foreign('permissionId')->references('id')->on('permission');
            $table->foreign('roleId')->references('id')->on('role');
            $table->foreign('planId')->references('id')->on('plan');
            $table->index(['roleId','permissionId','planId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_plan_permission');
    }
}
