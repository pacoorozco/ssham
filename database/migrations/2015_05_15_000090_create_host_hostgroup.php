<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHostHostgroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_hostgroup', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('host_id')->unsigned();
            $table->foreign('host_id')->references('id')->on('hosts')->onDelete('cascade');
            $table->integer('hostgroup_id')->unsigned();
            $table->foreign('hostgroup_id')->references('id')->on('hostgroups')->onDelete('cascade');
            $table->unique(array('host_id', 'hostgroup_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('host_hostgroup');
    }
}
