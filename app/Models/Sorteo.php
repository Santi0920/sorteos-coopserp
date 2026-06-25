<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sorteo extends Model
{
    use HasFactory;

    protected $table = 'sorteos';

    protected $fillable = [

        'nombre',

        'fecha_sorteo',

        'loteria',

        'estado',

        'numero_inicio',

        'numero_fin',

        'tipo_asignacion',

        'boletas_por_persona',

        'texto_promocional',

        'activo',

        'es_reprogramado',

        'observaciones',

        'numero_resultado',

        'soporte_resultado',

        'boletas_generadas',

    ];

    protected $casts = [

        'fecha_sorteo' => 'date',

        'es_reprogramado' => 'boolean',

        'activo' => 'boolean',

        'boletas_generadas' => 'boolean',

        'numero_inicio' => 'integer',

        'numero_fin' => 'integer',

        'boletas_por_persona' => 'integer',

    ];

    public function premios()
    {
        return $this->hasMany(Premio::class);
    }

    public function boletas()
    {
        return $this->hasMany(Boleta::class);
    }

    public function asociados()
    {
        return $this->belongsToMany(
            Asociado::class,
            'sorteo_asociado',
            'sorteo_id',
            'asociado_id'
        )
        ->withPivot([
            'boletas_por_persona',
            'email',
            'telefono',
            'whatsapp',
            'cuenta',
            'agencia',
            'nomina',
            'coordinador',
            'dependencia',
        ])
        ->withTimestamps();
    }

    public function design()
    {
        return $this->hasOne(
            BoletaDesign::class
        );
    }

    public function imports()
    {
        return $this->hasMany(Import::class);
    }
}