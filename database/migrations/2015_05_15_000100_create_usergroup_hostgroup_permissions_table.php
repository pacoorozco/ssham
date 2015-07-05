<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsergroupHostgroupPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usergroup_hostgroup_permissions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('usergroup_id')->unsigned();
            $table->foreign('usergroup_id')->references('id')->on('usergroups')->onDelete('cascade');
            $table->integer('hostgroup_id')->unsigned();
            $table->foreign('hostgroup_id')->references('id')->on('hostgroups')->onDelete('cascade');
            $table->enum('action', ['allow', 'deny'])->default('allow');
            $table->string('name')->nullable();
            $table->boolean('enabled')->default('1');
            $table->timestamps();
            $table->unique(array('usergroup_id', 'hostgroup_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('usergroup_hostgroup_permissions');
    }
}
