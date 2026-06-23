<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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
        'cuenta',
        'boletas_por_persona',
        'agencia',
        'nomina',
        'coordinador',
        'dependencia',
        'consentimiento_datos_at'
    ];

    protected static function booted(): void
    {
        static::saving(function (Asociado $asociado) {
            if (blank($asociado->token_consulta)) {
                $asociado->token_consulta = self::generarTokenConsultaUnico();
            }
        });
    }

    public static function generarTokenConsultaUnico(): string
    {
        do {
            $token = Str::random(64);
        } while (self::where('token_consulta', $token)->exists());

        return $token;
    }

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

    public function sorteos()
    {
        return $this->belongsToMany(
            Sorteo::class,
            'sorteo_asociado',
            'asociado_id',
            'sorteo_id'
        )->withTimestamps();
    }
}