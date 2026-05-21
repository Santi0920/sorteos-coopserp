<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boleta extends Model
{
    use HasFactory;

    protected $table = 'boletas';

    protected $fillable = [
        'sorteo_id',
        'asociado_id',
        'credito_id',
        'numero_boleta',
        'monto_base',
        'bloque_boletas',
        'ganadora',
    ];

    protected $casts = [
        'ganadora' => 'boolean',
        'monto_base' => 'decimal:2',
    ];

    public function sorteo()
    {
        return $this->belongsTo(Sorteo::class);
    }

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }

    public function credito()
    {
        return $this->belongsTo(Credito::class);
    }

    public function envios()
    {
        return $this->hasMany(BoletaEnvio::class);
    }

    public function premiosGanados()
    {
        return $this->hasMany(Premio::class, 'boleta_ganadora_id');
    }
}