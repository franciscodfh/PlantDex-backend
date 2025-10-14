<?php

namespace App\Http\Controllers;

use App\Models\PlantProposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlantProposalController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plant_id' => 'required|exists:plants,id',
            'scientific_name' => 'required|string|max:255',
            'common_name' => 'nullable|string|max:255',
            'user_description' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $proposal = PlantProposal::create([
            'user_id' => $request->user()->id,
            'plant_id' => $request->plant_id,
            'scientific_name' => $request->scientific_name,
            'common_name' => $request->common_name,
            'user_description' => $request->user_description,
            'status' => 'pending',
        ]);

        return response()->json([
            'proposal' => $proposal,
            'message' => 'Propuesta enviada exitosamente. Será revisada por un administrador.'
        ], 201);
    }

    public function index(Request $request)
    {
        $proposals = $request->user()->proposals()
                            ->with('plant')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return response()->json(['proposals' => $proposals]);
    }

    // Admin endpoints
    public function pending(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $proposals = PlantProposal::pending()
                                  ->with(['user', 'plant'])
                                  ->orderBy('created_at', 'asc')
                                  ->paginate(20);

        return response()->json($proposals);
    }

    public function approve(Request $request, PlantProposal $proposal)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $proposal->update([
            'status' => 'approved',
            'reviewed_by' => $request->user()->id,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        // Actualizar descripción de la planta
        $proposal->plant->update([
            'description' => $proposal->user_description,
        ]);

        return response()->json([
            'proposal' => $proposal,
            'message' => 'Propuesta aprobada exitosamente'
        ]);
    }

    public function reject(Request $request, PlantProposal $proposal)
    {
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'admin_notes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $proposal->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'proposal' => $proposal,
            'message' => 'Propuesta rechazada'
        ]);
    }
}
