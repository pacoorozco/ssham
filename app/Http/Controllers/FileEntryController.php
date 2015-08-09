<?php

namespace SSHAM\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use SSHAM\FileEntry;

class FileEntryController extends Controller {

    /**
     * Gets a file to download
     *
     * @param $filename
     * @return Response
     */
    public function get($filename)
    {
        // If file doesn't exists sends an 404 error
        if ( ! Storage::disk('local')->exists($filename)) {
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
