<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AzureStorageController;
use App\Http\Controllers\NotificationController;

Route::prefix('storage/azure')->group(function () {
    Route::post('upload', [AzureStorageController::class, 'upload'])->name('azure.upload.api');
});



Route::post('/notify', [NotificationController::class, 'send']);
Route::get('/negotiate', [NotificationController::class, 'negotiate']);

