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
            $table->integer('user_id')->unsigned()->comment('userGroup_id');
            $table->integer('userGroup_id')->unsigned()->comment('userGroup_id');
            $table->integer('role_id')->unsigned()->comment('role_id');
            $table->index(['role_id','userGroup_id','user_id'])->unique();
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
