<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Boletas</title>

    <link rel="shortcut icon" href="logoo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(37,99,235,.20), transparent 30%),
                radial-gradient(circle at bottom right, rgba(59,130,246,.18), transparent 30%),
                linear-gradient(135deg, #0f172a, #111827);
            color: #fff;
        }

        .main-card {
            max-width: 1200px;
            margin: 40px auto;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.10);
            backdrop-filter: blur(12px);
            border-radius: 28px;
            padding: 28px;
            box-shadow: 0 30px 60px rgba(0,0,0,.25);
        }

        .metric-card {
            background: rgba(255,255,255,.06);
            border-radius: 18px;
            padding: 18px;
            text-align: center;
        }

        .grid-boletas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 10px;
            margin-top: 25px;
            max-height: 500px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .boleta {
            padding: 10px;
            text-align: center;
            border-radius: 12px;
            font-weight: 700;
            font-size: .85rem;
        }

        .asignada {
            background: #22c55e;
            color: #022c22;
        }

        .pendiente {
            background: rgba(255,255,255,.10);
            color: rgba(255,255,255,.6);
        }

        .legend {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .legend span {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .9rem;
        }

        .dot {
            width: 14px;
            height: 14px;
            border-radius: 4px;
        }

        .dot-green { background: #22c55e; }
        .dot-gray { background: rgba(255,255,255,.3); }

    </style>
</head>
<body>

<div class="main-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
    
    <a href="{{ route('landing') }}" class="btn btn-light rounded-4">
        <i class="bi bi-arrow-left me-1"></i> Volver al inicio
    </a>

    <span class="text-soft small">Vista pública</span>

</div>
    <div class="text-center mb-4">
        <div class="brand-icon mb-3">
            <i class="bi bi-grid"></i>
        </div>
        <h2 class="fw-bold">Mapa de Boletas</h2>
        <p class="text-soft">Visualización pública de asignación de boletas</p>
    </div>

    @php
        $total = 10000;
        $asignadasCount = count($boletasAsignadas);
        $pendientesCount = $total - $asignadasCount;
    @endphp

    <div class="row text-center mb-3">
        <div class="col-md-4">
            <div class="metric-card">
                <h4>{{ number_format($total) }}</h4>
                <small>Total</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card">
                <h4>{{ number_format($asignadasCount) }}</h4>
                <small>Asignadas</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card">
                <h4>{{ number_format($pendientesCount) }}</h4>
                <small>Pendientes</small>
            </div>
        </div>
    </div>

    <div class="legend">
        <span><div class="dot dot-green"></div> Asignada</span>
        <span><div class="dot dot-gray"></div> Disponible</span>
    </div>

    <div class="grid-boletas">
        @for($i = 0; $i < 10000; $i++)
            @php
                $numero = str_pad($i, 4, '0', STR_PAD_LEFT);
                $esAsignada = in_array($numero, $boletasAsignadas);
            @endphp

            <div class="boleta {{ $esAsignada ? 'asignada' : 'pendiente' }}">
                {{ $numero }}
            </div>
        @endfor
    </div>

</div>

</body>
</html>