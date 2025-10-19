<?php

use App\Http\Controllers\ProfileController;
use App\Models\Sensor;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::post('/door-control', function (Request $request) {
    $data = $request->json()->all();

    // Log for debugging
    \Log::info('RP2040W Data:', $data);

    // Store in session for AJAX dashboard
    session(['telemetry' => array_merge($data, [
        'LastUpdate' => now()->format('Y-m-d H:i:s')
    ])]);

    return response()->json(['status' => 'received']);
});





Route::get('/door-control-view', function () {    
    return view('door-control',);
});

Route::get('/door-control-data', function () {
    $telemetry = session('telemetry', [
        'Light' => '—',
        'Mode' => '—',
        'Status' => '—',
        'Temperature' => '—',
        'Humidity' => '—',
        'LastUpdate' => '—',
    ]);

    return response()->json($telemetry);
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
    
    
 
});

require __DIR__.'/auth.php';
