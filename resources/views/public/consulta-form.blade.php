<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Boletas</title>
    <link rel="shortcut icon" href="logoo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(37,99,235,.20), transparent 30%),
                radial-gradient(circle at bottom right, rgba(59,130,246,.18), transparent 30%),
                linear-gradient(135deg, #0f172a, #111827);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .consulta-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.10);
            backdrop-filter: blur(10px);
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 30px 60px rgba(0,0,0,.25);
        }

        .brand-icon {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 18px;
            box-shadow: 0 18px 35px rgba(37,99,235,.35);
        }

        .form-control {
            border-radius: 14px;
            padding: 14px 16px;
            border: 1px solid rgba(255,255,255,.12);
        }

        .btn-primary {
            border-radius: 14px;
            padding: 13px 18px;
            font-weight: 600;
        }

        .text-soft {
            color: rgba(255,255,255,.74);
        }
    </style>
</head>
<body>
    <div class="consulta-card">
            
        <a href="{{ route('landing') }}" class="btn btn-light rounded-4">
            <i class="bi bi-arrow-left me-1"></i> Volver al inicio
        </a>
        <div class="text-center mb-4">
            <div class="brand-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>
            <h2 class="fw-bold mb-2">Consulta tus boletas</h2>
            <p class="text-soft mb-0">
                Ingresa tu documento para revisar las boletas registradas en el sistema.
            </p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger rounded-4 border-0">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-4 border-0">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('consulta.boletas.search') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Documento</label>
                <input
                    type="text"
                    name="documento"
                    class="form-control"
                    value="{{ old('documento') }}"
                    placeholder="Ingresa tu número de documento"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-1"></i> Consultar boletas
            </button>
        </form>
    </div>
</body>
</html>