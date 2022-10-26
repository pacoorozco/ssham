<?php

namespace App\Http\Controllers;

use App\Events\PrivateKeyWasDownloaded;
use App\Models\Key;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadPrivateKeyController extends Controller
{
    public function __invoke(Key $key): StreamedResponse
    {
        $this->authorize('update', $key);

        abort_unless($key->hasPrivateKey(), ResponseCode::HTTP_NOT_FOUND);

        // Private key content should be stored in a variable before dispatching
        // the event, which eventually will set null the value.
        $privateKeyContent = $key->private;

        PrivateKeyWasDownloaded::dispatch($key);

        return $this->privateKeyFileDownloadResponse($privateKeyContent, 'id_rsa');
    }

    private function privateKeyFileDownloadResponse(string $privateKeyContent, string $filename): StreamedResponse
    {
        return response()->streamDownload(
            function () use ($privateKeyContent) {
            echo "$privateKeyContent";
        },
            $filename,
            [
                'Content-Type' => 'application/pkcs8',
            ]
        );
    }
}
