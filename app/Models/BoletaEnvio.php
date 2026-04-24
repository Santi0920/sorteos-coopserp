<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BoletaEnvio extends Model
{
    use HasFactory;

    protected $table = 'boleta_envios';

    protected $fillable = [
        'boleta_id',
        'canal',
        'destino',
        'estado',
        'fecha_envio',
        'respuesta',
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
    ];

    public function boleta()
    {
        return $this->belongsTo(Boleta::class);
    }
}