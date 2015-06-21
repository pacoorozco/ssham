<?php

namespace SSHAM\Http\Controllers;

use SSHAM\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use SSHAM\Fileentry;

class FileEntryController extends Controller
{
    /**
     * Gets a file to download
     * 
     * @param type $filename
     * @return type
     */
    public function get($filename)
    {
        $entry = Fileentry::where('filename', '=', $filename)->firstOrFail();

        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        
        return response()->download(
            $storagePath . '/' . $filename,
            $entry->original_filename
            );
    }

}
