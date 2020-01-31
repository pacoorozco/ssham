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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2017 - 2020 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/ssham
 */

namespace App\Http\Controllers;

use App\FileEntry;
use Illuminate\Support\Facades\Storage;

class FileEntryController extends Controller
{

    /**
     * Gets a file to download
     *
     * @param $filename
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function get($filename)
    {
        // If file doesn't exists sends an 404 error
        if (!Storage::disk('local')->exists($filename)) {
            abort(404);
        }

        // Obtain information for file download
        $entry = FileEntry::where('filename', '=', $filename)->firstOrFail();
        $original_filename = $entry->original_filename;

        // Get local storage path
        $filename_path = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $filename;

        // Remove FileEntry
        $entry->delete();

        // Returns file to be downloaded and deletes after finish
        return response()->download(
            $filename_path,
            $original_filename
        )->deleteFileAfterSend(true);
    }

}
