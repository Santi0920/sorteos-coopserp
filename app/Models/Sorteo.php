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
        'es_reprogramado',
        'sorteo_padre_id',
        'observaciones',
    ];

    protected $casts = [
        'fecha_sorteo' => 'date',
        'es_reprogramado' => 'boolean',
    ];

    public function premios()
    {
        return $this->hasMany(Premio::class)->orderBy('orden');
    }
}