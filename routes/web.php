<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/door-control', function (Request $request) {
    $data = $request->json()->all();
    \Log::info('RP2040W Data:', $data);
    return response()->json(['status' => 'received']);
});






Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');




Route::middleware('auth')->group(function () { // protection middleware
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::view('/sunblock-net', 'sunblock-net')->name('sunblock.net');
    Route::view('/door-control-view', 'door-control')->middleware('auth')->name('door.control');

    


});

require __DIR__.'/auth.php';
