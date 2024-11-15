<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupoController;

Route::post('/grupos/crear', [GrupoController::class, 'crearGrupo']);
Route::get('/grupos', [GrupoController::class, 'listar']);
Route::post('/grupos/{id}/unirse', [GrupoController::class, 'unirse']);
Route::get('/grupos/{grupo_id}/miembros', [GrupoController::class, 'obtenerMiembros']);