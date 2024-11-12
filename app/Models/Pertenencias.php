<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertenencias extends Model
{
    use HasFactory;

    protected $table = 'pertenencias';

    protected $fillable = [
        'user_id', 'grupo_id', 'administrador', 'fecha_ingreso'
    ];

    public function isAdmin()
    {
        return $this->administrador;
    }
}
