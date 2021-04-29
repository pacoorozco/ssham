<?php
/**
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2020 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 - 2020 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Console\Commands;

use App\Jobs\UpdateServer;
use App\Models\Host;
use Illuminate\Console\Command;

class SendKeysToHosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssham:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SSH keys to managed hosts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $hosts = Host::enabled()->get();
        $this->info("Hosts to be updated: {$hosts->count()}");

        foreach ($hosts as $host) {
            $this->info("Updating key for {$host->full_hostname}");
            UpdateServer::dispatch($host);
        }

        return 0;
    }
}
