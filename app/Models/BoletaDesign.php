<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoletaDesign extends Model
{
    protected $fillable = [
        'sorteo_id',
        'logo',
        'titulo',
        'subtitulo',
        'descripcion',
        'terminos',
        'url_consulta_ganador',
        'texto_coljuegos',
    ];

    public function sorteo()
    {
        return $this->belongsTo(Sorteo::class);
    }
}

