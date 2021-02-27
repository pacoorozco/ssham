<?php
/*
 * SSH Access Manager - SSH keys management solution.
 *
 * Copyright (c) 2017 - 2021 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2021 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Listeners;

use App\Events\KeyAction;

class KeyActionLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  KeyAction  $event
     *
     * @return void
     */
    public function handle(KeyAction $event)
    {
        $key = $event->getKey();

        switch ($event->getAction()) {
            case 'create':
                $text = __('Create key \':username\'', [
                    'username' => $key->username,
                ]);
                break;
            case 'update':
                $text = __('Update key \':username\'', [
                    'username' => $key->username,
                ]);
                break;
            case 'destroy':
                $text = __('Delete key \':username\'', [
                    'username' => $key->username,
                ]);
                break;
            default:
                $text = __('Unknown action \':action\' on key \':username\'', [
                    'action' => $event->getAction(),
                    'username' => $key->username,
                ]);
                break;
        }

        activity()
            //->performedOn($key) // Key model is using UUID and Activity can't store it on the table.
            ->withProperties([
                'status' => $event->getStatus(),
            ])->log($text);
    }
}
