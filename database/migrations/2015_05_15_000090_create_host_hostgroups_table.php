<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2019 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2019 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/ssham
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostHostgroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('host_hostgroup', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('host_id')->unsigned();
            $table->foreign('host_id')->references('id')->on('hosts')->onDelete('cascade');
            $table->unsignedBigInteger('hostgroup_id')->unsigned();
            $table->foreign('hostgroup_id')->references('id')->on('hostgroups')->onDelete('cascade');
            $table->unique(['host_id', 'hostgroup_id']);
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
