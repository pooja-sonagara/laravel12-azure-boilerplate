
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AzureStorageController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

Route::get('/', function () {
    return view('welcome');
});

// Azure authentication routes
Route::get('auth/azure', function () {
    return Socialite::driver('azure')->redirect();
})->name('login.azure');

Route::get('auth/azure/callback', function () {
    $user = Socialite::driver('azure')->stateless()->user();
    // Handle login or registration logic here
    dd($user);
});


Route::prefix('storage/azure')->group(function () {
    Route::get('/', [AzureStorageController::class, 'index'])->name('azure.index');
    Route::post('upload', [AzureStorageController::class, 'upload'])->name('azure.upload');
    Route::get('download/{filename}', [AzureStorageController::class, 'download'])->name('azure.download');


    // Route::get('download/{file}', function ($file) {
    //     $disk = Storage::disk('azure'); // your Azure disk
    //     if (!$disk->exists($file)) {
    //         abort(404);
    //     }

    //     // Get file stream
    //     $stream = $disk->readStream($file);

    //     return new StreamedResponse(function () use ($stream) {
    //         fpassthru($stream);
    //     }, 200, [
    //         "Content-Type"        => $disk->mimeType($file), // correct MIME type
    //         "Content-Length"      => $disk->size($file),     // file size
    //         "Content-Disposition" => "attachment; filename=\"" . basename($file) . "\"",
    //     ]);
    // })->name('azure.download');
});
