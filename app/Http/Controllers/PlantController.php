<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlantController extends Controller
{
    public function index(Request $request)
    {
        $plants = $request->user()->plants()
                          ->orderBy('captured_date', 'desc')
                          ->paginate(20);

        return response()->json($plants);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'scientific_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
            'type' => 'required|in:plant,fungi',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'confidence' => 'nullable|integer|min:0|max:100',
            'description' => 'nullable|string',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plants', 'public');
        }

        $plant = Plant::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'scientific_name' => $request->scientific_name,
            'nickname' => $request->nickname,
            'family' => $request->family,
            'type' => $request->type,
            'image_path' => $imagePath,
            'confidence' => $request->confidence,
            'description' => $request->description,
            'captured_date' => now(),
            'location_lat' => $request->location_lat,
            'location_lng' => $request->location_lng,
        ]);

        return response()->json([
            'plant' => $plant->load('user'),
            'message' => 'Planta guardada exitosamente'
        ], 201);
    }

    public function show(Request $request, Plant $plant)
    {
        if ($plant->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($plant);
    }

    public function update(Request $request, Plant $plant)
    {
        if ($plant->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nickname' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $plant->update($request->only(['nickname', 'description']));

        return response()->json([
            'plant' => $plant,
            'message' => 'Planta actualizada exitosamente'
        ]);
    }

    public function destroy(Request $request, Plant $plant)
    {
        if ($plant->user_id !== $request->user()->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Eliminar imagen del storage
        if ($plant->image_path) {
            Storage::disk('public')->delete($plant->image_path);
        }

        $plant->delete();

        return response()->json([
            'message' => 'Planta eliminada exitosamente',
            'plant_name' => $plant->name
        ]);
    }

    public function identify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simulación de identificación de planta
        $mockIdentifications = [
            [
                'name' => 'Rosa gallica',
                'scientific_name' => 'Rosa gallica',
                'family' => 'Rosaceae',
                'confidence' => 85,
                'type' => 'plant'
            ],
            [
                'name' => 'Girasol',
                'scientific_name' => 'Helianthus annuus',
                'family' => 'Asteraceae',
                'confidence' => 92,
                'type' => 'plant'
            ],
            [
                'name' => 'Tulipán',
                'scientific_name' => 'Tulipa gesneriana',
                'family' => 'Liliaceae',
                'confidence' => 78,
                'type' => 'plant'
            ],
            [
                'name' => 'Lavanda',
                'scientific_name' => 'Lavandula angustifolia',
                'family' => 'Lamiaceae',
                'confidence' => 88,
                'type' => 'plant'
            ],
        ];

        $identification = $mockIdentifications[array_rand($mockIdentifications)];

        return response()->json([
            'identification' => $identification,
            'message' => 'Planta identificada exitosamente'
        ]);
    }
}