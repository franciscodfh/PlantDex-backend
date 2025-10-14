<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plant;
use App\Models\PlantProposal;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $stats = [
            'total_users' => User::count(),
            'total_plants' => Plant::count(),
            'pending_proposals' => PlantProposal::where('status', 'pending')->count(),
            'approved_proposals' => PlantProposal::where('status', 'approved')->count(),
            'rejected_proposals' => PlantProposal::where('status', 'rejected')->count(),
        ];

        return response()->json($stats);
    }
    
    public function updateAllCounts(Request $request)
    {
        try {
            $users = User::all();
            $updated = 0;
            
            foreach ($users as $user) {
                $oldCount = $user->plants_count;
                $user->updatePlantsCount();
                if ($oldCount !== $user->plants_count) {
                    $updated++;
                }
            }
            
            return response()->json([
                'message' => 'Contadores actualizados exitosamente',
                'total_users' => $users->count(),
                'updated_users' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error actualizando contadores: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteAllPlants(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        try {
            $deletedCount = Plant::count();
            Plant::query()->delete(); // Elimina todas las plantas
            
            // Resetear conteos de todos los usuarios
            User::query()->update(['plants_count' => 0]);
            
            return response()->json([
                'message' => 'Todas las plantas eliminadas exitosamente',
                'deleted_plants' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error eliminando plantas: ' . $e->getMessage()
            ], 500);
        }
    }
}
