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
 *
 * @link        https://github.com/pacoorozco/ssham
 */

namespace App\Console\Commands;

use App\Jobs\UpdateServer;
use App\Models\Host;
use Illuminate\Console\Command;

class SendKeysToHostsCommand extends Command
{
    protected $signature = 'ssham:send
                            {--pending : only host with pending changes will be updated}';

    protected $description = 'Update the authorized SSH keys file on the remote hosts';

    public function handle(): int
    {
        $query = Host::query()
                ->enabled();

        if ($this->option('pending')) {
            $query->withPendingChanges();
        }

        $hosts = $query->get();

        if ($hosts->count() === 0) {
            $this->info('There are not pending servers.');

            return self::SUCCESS;
        }

        $this->info("Hosts to be updated: {$hosts->count()}");
        $this->newLine();

        foreach ($hosts as $host) {
            $this->info("-> Queueing job for {$host->full_hostname}...");

            try {
                UpdateServer::dispatch($host);
            } catch (\Throwable $exception) {
                $this->error($exception->getMessage());
            }
        }

        $this->newLine();
        $this->info('All done!');

        return self::SUCCESS;
    }
}
