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
 * @link        https://github.com/pacoorozco/ssham
 */

use App\Enums\ControlRuleAction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostgroupKeygroupPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostgroup_keygroup_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('source_id')->unsigned();
            $table->foreign('source_id')->references('id')->on('keygroups')->onDelete('cascade');
            $table->unsignedBigInteger('target_id')->unsigned();
            $table->foreign('target_id')->references('id')->on('hostgroups')->onDelete('cascade');
            $table->enum('action', ControlRuleAction::getValues())->default(ControlRuleAction::Allow);
            $table->string('name')->nullable();
            $table->boolean('enabled')->default('1');
            $table->timestamps();
            $table->unique(['source_id', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hostgroup_keygroup_permissions');
    }
}
