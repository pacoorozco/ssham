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

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostgroup_keygroup_permissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('source_id')
                ->constrained('keygroups')
                ->cascadeOnDelete();

            $table->foreignId('target_id')
                ->constrained('hostgroups')
                ->cascadeOnDelete();

            $table->string('name')
                ->nullable();

            $table->boolean('enabled')
                ->default('1');

            $table->timestamps();

            $table->unique(['source_id', 'target_id']);
        });
    }

    public function down(): void
    {
        Schema::drop('hostgroup_keygroup_permissions');
    }
};
