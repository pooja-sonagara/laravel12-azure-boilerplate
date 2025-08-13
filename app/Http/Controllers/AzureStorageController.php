<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use Symfony\Component\HttpFoundation\StreamedResponse;


class AzureStorageController extends Controller
{
    public function index()
    {
        $files = Storage::disk('azure')->files();
        return view('storage.azure.index', compact('files'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
        ]);
        $path = Storage::disk('azure')->putFile('', $request->file('file'));
        return redirect()->route('azure.index')->with('success', 'File uploaded: ' . $path);
    }


    public function download($file)
    {
        if (!Storage::disk('azure')->exists($file)) {
            abort(404, 'File not found.');
        }

        $stream = Storage::disk('azure')->readStream($file);

        return response()->streamDownload(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, basename($file), [
            'Content-Type'        => 'application/octet-stream', // force download
            'Content-Disposition' => 'attachment; filename="' . basename($file) . '"',
            'Cache-Control'       => 'no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }


    public function downloadOld($filename)
    {
        if (!Storage::disk('azure')->exists($filename)) {
            abort(404, 'File not found in Azure Blob Storage');
        }

        return response()->streamDownload(function () use ($filename) {
            $stream = Storage::disk('azure')->readStream($filename);

            if ($stream === false) {
                abort(500, 'Unable to read file from Azure Blob Storage');
            }

            // Output the file exactly as it is
            fpassthru($stream);
            fclose($stream);
        }, basename($filename), [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"'
        ]);
    }
}
