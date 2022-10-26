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

use App\Enums\HostStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hostname');
            $table->string('username');
            $table->integer('port')->unsigned()->nullable();
            $table->string('authorized_keys_file')->nullable();
            $table->enum('type', ['linux'])->default('linux');
            $table->string('key_hash')->nullable();
            $table->boolean('enabled')->default(true);
            $table->boolean('synced')->default(false);
            $table->string('status_code')->default(HostStatus::INITIAL_STATUS);
            $table->timestamp('last_rotation')->nullable();
            $table->timestamps();
            $table->index(['hostname', 'username']);
        });
    }

    public function down(): void
    {
        Schema::drop('hosts');
    }
};
