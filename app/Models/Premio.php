<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Premio extends Model
{
    use HasFactory;

    protected $table = 'premios';

    protected $fillable = [

        'sorteo_id',

        'boleta_ganadora_id',

        'titulo',

        'descripcion',

        'imagen',

        'orden',

        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function sorteo()
    {
        return $this->belongsTo(Sorteo::class);
    }

    public function boletaGanadora()
    {
        return $this->belongsTo(
            Boleta::class,
            'boleta_ganadora_id'
        );
    }
}