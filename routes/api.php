<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrupoController;

Route::post('/grupos', [GrupoController::class, 'crear']);
Route::get('/grupos', [GrupoController::class, 'listar']);
Route::post('/grupos/{id}/unirse', [GrupoController::class, 'unirse']);