<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asociado extends Model
{
    use HasFactory;

    protected $table = 'asociados';

    protected $fillable = [
        'documento',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'whatsapp',
        'token_consulta',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function creditos()
    {
        return $this->hasMany(Credito::class);
    }

    public function boletas()
    {
        return $this->hasMany(Boleta::class);
    }

    public function getNombreCompletoAttribute()
    {
        return trim(($this->nombres ?? '') . ' ' . ($this->apellidos ?? ''));
    }
}