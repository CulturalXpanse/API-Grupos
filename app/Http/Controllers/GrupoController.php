<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Support\Str;
use App\Models\Pertenencias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GrupoController extends Controller
{
    public function crearGrupo(Request $request) {
    
        $grupo = new Grupo();
        $grupo->user_id = $request->user_id;
        $grupo->nombre = $request->nombre;
        $grupo->descripcion = $request->descripcion;
        $grupo->publico = $request->publico;
    
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $mimeType = $foto->getMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
    
            if (!in_array($mimeType, $allowedMimeTypes)) {
                return response()->json(['error' => 'Solo se permiten imágenes (jpeg, png, gif, svg)'], 400);
            }
    
            $fileName = Str::random(50) . '.' . $foto->getClientOriginalExtension();
            $destinationPath = 'fotos/grupos';
            $foto->move($destinationPath, $fileName);
            $grupo->foto = $fileName;
        }
    
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $mimeType = $banner->getMimeType();
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
    
            if (!in_array($mimeType, $allowedMimeTypes)) {
                return response()->json(['error' => 'Solo se permiten imágenes (jpeg, png, gif, svg)'], 400);
            }
    
            $fileName = Str::random(50) . '.' . $banner->getClientOriginalExtension();
            $destinationPath = 'banners/grupos';
            $banner->move($destinationPath, $fileName);
            $grupo->banner = $fileName;
        }
    
        $grupo->save();
    
        Pertenencias::create([
            'user_id' => $user_id->id,
            'grupo_id' => $grupo->id,
            'administrador' => true,
        ]);
    
        return response()->json(['grupo' => $grupo], 201);
    }
    

    public function listar() {
        $grupos = Grupo::all();
        return response()->json(['grupos' => $grupos], 200);
    }

    public function unirse(Request $request, $id) {
    $accessToken = $request->header('Authorization');

    $response = Http::withHeaders([
        'Authorization' => $accessToken
    ])->get('http://localhost:8000/api/validate');

    if ($response->successful()) {
        $userData = $response->json();
        $userId = $userData['user_id'];

        $grupo = Grupo::findOrFail($id);

        Pertenencias::updateOrCreate(
            ['user_id' => $userId, 'grupo_id' => $grupo->id],
            ['administrador' => false]
        );

        return response()->json(['message' => 'Unido al grupo'], 200);
    } else {
        return response()->json(['error' => 'No autenticado'], 401);
    }
}
}
