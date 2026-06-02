<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PlantillaAsociadosExport implements FromArray
{
    public function array(): array
    {
        return [

            [
                'Documento',
                'Nombres',
                'Apellidos',
                'Email',
                'Telefono',
                'Cuenta',
                'Boletas por Persona',
                'Agencia',
                'Nomina',
                'Coordinador',
                'Dependencia'
            ],

            [
                '1002345678',
                'Juan David',
                'Ramirez Perez',
                'juan@email.com',
                '3001234567',
                '102030',
                '10',
                'Medellin',
                'COOPSERP EMPLEADOS',
                'Carolina Gonzalez',
                'Empleados'
            ]

        ];
    }
}