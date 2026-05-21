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
        'monto_por_boleta',
        'es_reprogramado',
        'observaciones',
        'numero_resultado',
        'soporte_resultado',
    ];

    protected $casts = [
        'fecha_sorteo' => 'date',
        'es_reprogramado' => 'boolean',
        'activo' => 'boolean',
        'numero_inicio' => 'integer',
        'numero_fin' => 'integer',
    ];

    public function premios()
    {
        return $this->hasMany(Premio::class)->orderBy('orden');
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
        )->withTimestamps();
    }

    public function design()
    {
        return $this->hasOne(BoletaDesign::class);
    }
}