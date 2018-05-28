<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePanPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_file_permission', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('roleId')->unsigned()->comment('roleId');
            $table->integer('permissionId')->unsigned()->comment('permissionId');
            $table->integer('panId')->unsigned()->comment('panId');
            $table->foreign('permissionId')->references('id')->on('permission');
            $table->foreign('roleId')->references('id')->on('role');
            $table->foreign('panId')->references('id')->on('pan');
            $table->index(['roleId','permissionId','panId'])->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_file_permission');
    }
}
