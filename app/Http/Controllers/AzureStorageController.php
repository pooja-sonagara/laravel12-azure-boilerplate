<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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
        $disk = Storage::disk('azure');
        $uploaded = $request->file('file');
        $originalName = $uploaded->getClientOriginalName();
        $name = str_replace(['#', '?', '%'], '_', $originalName);
        if ($disk->exists($name)) {
            return back()->withErrors(['file' => 'File already exists: ' . $name])->withInput();
        }
        $disk->putFileAs('', $uploaded, $name);
        return redirect()->route('azure.storage.index')->with('success', 'File uploaded: ' . $name);
    }
}
