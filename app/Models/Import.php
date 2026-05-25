<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'sorteo_id',
        'file_path',
        'rows_total',
        'rows_success',
        'rows_failed',
        'errors'
    ];

    protected $casts = [
        'errors' => 'array',
    ];

    public function sorteo()
    {
        return $this->belongsTo(Sorteo::class);
    }
}