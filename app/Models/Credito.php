<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Credito extends Model
{
    use HasFactory;

    protected $table = 'creditos';

    protected $fillable = [
        'asociado_id',
        'linea_credito_id',
        'numero_credito',
        'monto',
        'fecha_desembolso',
        'participa_sorteo',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_desembolso' => 'date',
        'participa_sorteo' => 'boolean',
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }

    public function lineaCredito()
    {
        return $this->belongsTo(LineaCredito::class, 'linea_credito_id');
    }

    public function boletas()
    {
        return $this->hasMany(Boleta::class);
    }
}