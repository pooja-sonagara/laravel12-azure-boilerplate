
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AzureStorageController;
use Laravel\Socialite\Facades\Socialite;

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
    Route::get('/', [AzureStorageController::class, 'index'])->name('azure.storage.index');
});


Route::view('/notifications', 'notifications');
