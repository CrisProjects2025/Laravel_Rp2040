Route::get('/door-data', fn() => response()->json([
    'door' => 'closed',
    'lock' => 'engaged',
    'last_updated' => now()
]));

Route::get('/sunblock-data', fn() => response()->json([
    'shade' => 'deployed',
    'light' => 'filtered',
    'last_updated' => now()
]));
