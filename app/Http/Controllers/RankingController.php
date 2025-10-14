<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 30);
            
            $users = User::orderBy('plants_count', 'desc')
                        ->orderBy('created_at', 'asc')
                        ->limit($limit)
                        ->get()
                        ->map(function ($user) {
                            return [
                                'id' => $user->id,
                                'name' => $user->nickname ?? $user->username,
                                'username' => $user->username,
                                'nickname' => $user->nickname ?? '',
                                'profile_image' => $user->profile_image ?? '',
                                'plantsCount' => $user->plants_count ?? 0,
                                'plants_count' => $user->plants_count ?? 0,
                                'registrationDate' => $user->created_at->format('Y-m-d'),
                                'registration_date' => $user->created_at->format('Y-m-d'),
                            ];
                        })
                        ->values();
            
            $ranking = $users->map(function ($user, $index) {
                $user['rank'] = $index + 1;
                return $user;
            });

            return response()->json([
                'ranking' => $ranking,
                'total_users' => User::count(),
                'message' => 'Ranking obtenido exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'ranking' => [],
                'total_users' => 0,
                'message' => 'Error obteniendo ranking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function userRank(Request $request)
    {
        $user = $request->user();
        
        $rank = User::where('plants_count', '>', $user->plants_count)
                   ->orWhere(function($query) use ($user) {
                       $query->where('plants_count', $user->plants_count)
                             ->where('registration_date', '<', $user->registration_date);
                   })
                   ->count() + 1;

        return response()->json([
            'user_rank' => $rank,
            'plants_count' => $user->plants_count,
            'total_users' => User::count(),
            'message' => 'Ranking del usuario obtenido exitosamente'
        ]);
    }

    public function stats()
    {
        $totalUsers = User::count();
        $totalPlants = \App\Models\Plant::count();
        $topUser = User::orderBy('plants_count', 'desc')->first();
        
        return response()->json([
            'stats' => [
                'total_users' => $totalUsers,
                'total_plants' => $totalPlants,
                'top_user' => $topUser ? [
                    'username' => $topUser->username,
                    'plants_count' => $topUser->plants_count
                ] : null,
                'average_plants_per_user' => $totalUsers > 0 ? round($totalPlants / $totalUsers, 2) : 0
            ],
            'message' => 'Estadísticas obtenidas exitosamente'
        ]);
    }
    
    public function updateUserPlantCount(Request $request)
    {
        $user = $request->user();
        $user->updatePlantsCount();
        
        return response()->json([
            'message' => 'Conteo de plantas actualizado',
            'plants_count' => $user->plants_count
        ]);
    }
    
    public function decrementUserPlantCount(Request $request)
    {
        $user = $request->user();
        $user->plants_count = max(0, $user->plants_count - 1);
        $user->save();
        
        return response()->json([
            'message' => 'Conteo de plantas decrementado',
            'plants_count' => $user->plants_count
        ]);
    }
}