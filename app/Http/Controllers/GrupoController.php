<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Pertenencias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GrupoController extends Controller
{
    public function crear(Request $request) {
    $request->validate([
        'nombre' => 'required|string',
        'descripcion' => 'required|string|max:500'
    ]);

    $token = $request->bearerToken();

    if (!$token) {
        return response()->json(['message' => 'Token de acceso requerido'], 401);
    }

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token
    ])->get('http://localhost:8000/api/validate');

    if ($response->status() != 200) {
        return response()->json(['message' => 'Token inválido o expirado'], 401);
    }

    $userId = $response->json()['id'];

    $grupo = Grupo::create([
        'user_id' => $userId,
        'foto' => $request->file('foto')->store('fotos'),
        'banner' => $request->file('banner')->store('banners'),
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'publico' => $request->publico,
    ]);

    Pertenencias::create([
        'user_id' => $userId,
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
