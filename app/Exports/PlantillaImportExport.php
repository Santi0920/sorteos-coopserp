<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PlantillaImportExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Cuenta','Agencia','Nomina','Cedula','Nombre','Linea','Credito','Monto','Email'],
            ['125463','Quibdo','Beneficencia','123456789','Juan Perez','99','15489','3900000','juan@coopserp.com'],
        ];
    }
}