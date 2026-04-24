<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaCredito extends Model
{
    protected $table = 'lineas_credito';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'participa_sorteo',
        'activo',
    ];

    public function creditos()
    {
        return $this->hasMany(Credito::class, 'linea_credito_id');
    }
}