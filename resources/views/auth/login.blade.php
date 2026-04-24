<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Sorteos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="logoo.png" type="image/png">

    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Inter, sans-serif;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 60px rgba(0,0,0,.25);
        }

        .login-title {
            font-weight: 800;
            font-size: 1.6rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px;
        }

        .btn-primary {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            background: #2563eb;
            border: none;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        /* LOGO SIN RECUADRO */
        .coopserp-logo {
            max-width: 220px; /* MÁS GRANDE 🔥 */
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }

        .coopserp-logo:hover {
            transform: scale(1.03);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-check-input {
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="text-center mb-4">

        <!-- LOGO LIMPIO -->
        <img 
            src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
            alt="Coopserp Logo"
            class="coopserp-logo"
        >

        <div class="login-title">Sorteos Admin Coopserp</div>
        <small class="text-muted">Inicia sesión para continuar</small>

    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="login">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Recordarme</label>
        </div>

        <button class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
        </button>
    </form>

</div>

</body>
</html>