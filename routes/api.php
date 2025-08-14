<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AzureStorageController;

Route::prefix('storage/azure')->group(function () {
    Route::post('upload', [AzureStorageController::class, 'upload'])->name('azure.upload.api');
});
