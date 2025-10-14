<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    public function getCurrent()
    {
        $announcement = DB::table('announcements')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'message' => $announcement ? $announcement->message : ''
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        // Desactivar anuncios anteriores
        DB::table('announcements')->update(['is_active' => false]);

        // Crear nuevo anuncio
        DB::table('announcements')->insert([
            'message' => $request->message,
            'is_active' => true,
            'created_by' => $request->user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Anuncio publicado exitosamente'
        ]);
    }
}