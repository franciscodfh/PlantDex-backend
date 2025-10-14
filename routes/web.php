<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Plantadex API',
        'version' => '1.0.0',
        'developer' => 'Fix Bit'
    ]);
});