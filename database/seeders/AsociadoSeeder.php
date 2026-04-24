<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AsociadoSeeder extends Seeder
{
    public function run(): void
    {
        $asociados = [
            [
                'documento' => '100000001',
                'nombres' => 'Carlos Alberto',
                'apellidos' => 'Pérez Gómez',
                'email' => 'carlos@example.com',
                'telefono' => '3001112233',
                'whatsapp' => '573001112233',
                'activo' => true,
            ],
            [
                'documento' => '100000002',
                'nombres' => 'María Fernanda',
                'apellidos' => 'López Díaz',
                'email' => 'maria@example.com',
                'telefono' => '3002223344',
                'whatsapp' => '573002223344',
                'activo' => true,
            ],
            [
                'documento' => '100000003',
                'nombres' => 'Juan Esteban',
                'apellidos' => 'Ramírez Torres',
                'email' => 'juan@example.com',
                'telefono' => '3003334455',
                'whatsapp' => '573003334455',
                'activo' => true,
            ],
        ];

        foreach ($asociados as $asociado) {
            DB::table('asociados')->updateOrInsert(
                ['documento' => $asociado['documento']],
                array_merge($asociado, [
                    'token_consulta' => Str::uuid()->toString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}