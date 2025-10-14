<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\PlantProposalController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/facebook-login', [AuthController::class, 'facebookLogin']);

// Ranking público
Route::get('/ranking', [RankingController::class, 'index']);
Route::get('/stats', [RankingController::class, 'stats']);

// Ruta de prueba temporal
Route::get('/test-delete-all', function() {
    $deletedCount = \App\Models\Plant::count();
    \App\Models\Plant::query()->delete();
    \App\Models\User::query()->update(['plants_count' => 0]);
    return response()->json(['message' => 'Test: Eliminadas ' . $deletedCount . ' plantas']);
});

// Identificación de plantas (puede ser pública o requerir auth)
Route::post('/identify', [PlantController::class, 'identify']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    
    // Plantas del usuario
    Route::apiResource('plants', PlantController::class);
    
    // Ranking del usuario
    Route::get('/my-rank', [RankingController::class, 'userRank']);
    Route::post('/user/update-plant-count', [RankingController::class, 'updateUserPlantCount']);
    Route::post('/user/decrement-plant-count', [RankingController::class, 'decrementUserPlantCount']);
    
    // Propuestas de plantas
    Route::post('/proposals', [App\Http\Controllers\PlantProposalController::class, 'store']);
    Route::get('/proposals', [App\Http\Controllers\PlantProposalController::class, 'index']);
    
    // Admin - Propuestas
    Route::get('/admin/proposals/pending', [PlantProposalController::class, 'pending']);
    Route::post('/admin/proposals/{proposal}/approve', [PlantProposalController::class, 'approve']);
    Route::post('/admin/proposals/{proposal}/reject', [PlantProposalController::class, 'reject']);
    
    // Admin - Stats
    Route::get('/admin/stats', [AdminController::class, 'stats']);
    Route::post('/admin/update-all-counts', [AdminController::class, 'updateAllCounts']);
    Route::delete('/admin/delete-all-plants', [AdminController::class, 'deleteAllPlants']);

});

// Anuncios
Route::get('/announcement', [App\Http\Controllers\AnnouncementController::class, 'getCurrent']);
Route::middleware('auth:sanctum')->post('/announcement', [App\Http\Controllers\AnnouncementController::class, 'store']);

// Rutas de prueba
Route::get('/test', function () {
    return response()->json([
        'message' => 'Plantadex API funcionando correctamente',
        'version' => '1.0.0',
        'developer' => 'Fix Bit',
        'timestamp' => now()
    ]);
});

Route::get('/ranking-test', function () {
    $users = \App\Models\User::count();
    $plants = \App\Models\Plant::count();
    return response()->json([
        'message' => 'Ranking test OK',
        'users_count' => $users,
        'plants_count' => $plants,
        'timestamp' => now()
    ]);
});

Route::get('/debug-plants', function () {
    $users = \App\Models\User::with('plants')->get();
    $debug = [];
    foreach ($users as $user) {
        $debug[] = [
            'user_id' => $user->id,
            'username' => $user->username,
            'plants_count_field' => $user->plants_count,
            'plants_relation_count' => $user->plants->count(),
            'plants' => $user->plants->pluck('name')
        ];
    }
    return response()->json($debug);
});

