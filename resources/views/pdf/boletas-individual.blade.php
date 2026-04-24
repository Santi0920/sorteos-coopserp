<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="shortcut icon" href="logoo.png" type="image/png">
    <meta charset="UTF-8">
    <title>Boletas del Asociado</title>
    <style>
        @page {
            margin: 28px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
        }

        .header {
            border: 1px solid #dbe3ef;
            border-radius: 14px;
            padding: 18px 20px;
            margin-bottom: 18px;
            background: #f8fbff;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #475569;
            font-size: 11px;
        }

        .grid {
            width: 100%;
            margin-bottom: 18px;
        }

        .grid td {
            vertical-align: top;
            width: 50%;
            padding: 8px 10px 8px 0;
        }

        .label {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .value {
            font-size: 12px;
            font-weight: bold;
            color: #111827;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 18px 0 10px;
            color: #0f172a;
        }

        .summary-box {
            border: 1px solid #dbe3ef;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 16px;
            background: #ffffff;
        }

        .summary-number {
            font-size: 20px;
            font-weight: bold;
            color: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 11px;
            text-transform: uppercase;
            padding: 10px 12px;
            border: 1px solid #dbe3ef;
            text-align: left;
        }

        tbody td {
            padding: 11px 12px;
            border: 1px solid #e5e7eb;
        }

        .pill {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .winner {
            background: #dcfce7;
            color: #166534;
            padding: 5px 10px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 10px;
        }

        .normal {
            background: #f1f5f9;
            color: #334155;
            padding: 5px 10px;
            border-radius: 999px;
            font-weight: bold;
            font-size: 10px;
        }

        .footer {
            margin-top: 24px;
            font-size: 10px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- LOGO -->
        <div style="text-align:center; margin-bottom:10px;">
            <img src="{{ public_path('logazo.png') }}" style="height:80px;">
        </div>

        <div class="title">Boletas del Asociado</div>
        <div class="subtitle">Documento individual para impresión y consulta</div>
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="label">Asociado</div>
                <div class="value">{{ $asociado->nombre_completo }}</div>
            </td>
            <td>
                <div class="label">Documento</div>
                <div class="value">{{ $asociado->documento }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Correo</div>
                <div class="value">{{ $asociado->email ?: 'No registrado' }}</div>
            </td>
            <td>
                <div class="label">WhatsApp</div>
                <div class="value">{{ $asociado->whatsapp ?: 'No registrado' }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="label">Cantidad total de boletas</div>
                <div class="value">{{ $boletas->count() }}</div>
            </td>
            <td>
                <div class="label">Fecha de generación</div>
                <div class="value">{{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <div class="summary-box">
        <div class="label">Total de boletas asignadas</div>
        <div class="summary-number">{{ $boletas->count() }}</div>
    </div>

    <div class="section-title">Listado de boletas</div>

    <table>
        <thead>
            <tr>
                <th style="width: 18%;">Número</th>
                <th style="width: 28%;">Sorteo</th>
                <th style="width: 18%;">Fecha</th>
                <th style="width: 22%;">Monto base</th>
                <th style="width: 14%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($boletas as $boleta)
                <tr>
                    <td>
                        <span class="pill">{{ $boleta->numero_boleta }}</span>
                    </td>
                    <td>{{ $boleta->sorteo?->nombre ?? '—' }}</td>
                    <td>{{ $boleta->sorteo?->fecha_sorteo ? $boleta->sorteo->fecha_sorteo->format('d/m/Y') : '—' }}</td>
                    <td>${{ number_format((float)$boleta->monto_base, 0, ',', '.') }}</td>
                    <td>
                        @if($boleta->ganadora)
                            <span class="winner">Gan.</span>
                        @else
                            <span class="normal">Act.</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado por el sistema de sorteos.
    </div>
</body>
</html>