
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">

<div style="
    max-width:650px;
    margin:30px auto;
    background:#ffffff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
">

    {{-- HEADER --}}
    <div style="
        background:#1e3a8a;
        padding:25px;
        color:#fff;
        text-align:center;
    ">

        <img
            src="{{ $message->embed(public_path('storage/logos/Coopserp.png')) }}"
            style="max-height:70px;margin-bottom:10px;"
        >

        <h2 style="margin:0;">
            🎟️ Boletas asignadas
        </h2>

        <p style="
            margin:8px 0 0;
            font-size:14px;
            opacity:.9;
        ">
            {{ $sorteo->nombre }}
        </p>

    </div>

    {{-- BODY --}}
    <div style="padding:30px;color:#333;">

        <h3 style="margin-top:0;">
            Hola {{ $asociado->nombre_completo }}
        </h3>

        <p style="
            font-size:14px;
            line-height:1.7;
        ">
            Te informamos que tus boletas para el sorteo
            <strong>{{ $sorteo->nombre }}</strong>
            fueron generadas y registradas correctamente.
        </p>

        <div style="
            background:#f1f5f9;
            padding:15px;
            border-radius:10px;
            margin:20px 0;
        ">
            <strong>Sorteo:</strong>
            {{ $sorteo->nombre }}
            <br>

            <strong>Fecha del sorteo:</strong>
            {{ $sorteo->fecha_sorteo->format('d/m/Y') }}
            <br>

            <strong>Total de boletas:</strong>
            {{ $boletas->count() }}
        </div>

        <h4 style="margin-bottom:10px;">
            📌 Boletas asignadas
        </h4>

        <table style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        ">

            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:10px;text-align:left;">
                        Número
                    </th>

                    <th style="padding:10px;text-align:left;">
                        Estado
                    </th>
                </tr>
            </thead>

            <tbody>

                @foreach($boletas as $boleta)

                    <tr style="border-bottom:1px solid #eee;">

                        <td style="padding:10px;">
                            {{ $boleta->numero_boleta }}
                        </td>

                        <td style="padding:10px;">

                            <span style="
                                background:#dcfce7;
                                color:#166534;
                                padding:4px 10px;
                                border-radius:20px;
                                font-size:12px;
                                font-weight:bold;
                            ">
                                Asignada
                            </span>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <div style="
            margin-top:25px;
            padding:15px;
            border:1px solid #e5e7eb;
            border-radius:10px;
            background:#fafafa;
        ">

            <h4 style="margin-top:0;">
                🌐 Enlaces Oficiales
            </h4>

            <p style="margin:8px 0;">
                <strong>Página oficial:</strong><br>
                http://190.66.10.150:10100/sorteos-coopserp/
            </p>

            <p style="margin:8px 0;">
                <strong>Consultar boletas:</strong><br>
                http://190.66.10.150:10100/sorteos-coopserp/consulta
            </p>

            <p style="margin:8px 0;">
                <strong>Consultar ganadores:</strong><br>
                http://190.66.10.150:10100/sorteos-coopserp/resultados
            </p>

        </div>

        <p style="
            margin-top:20px;
            font-size:13px;
            color:#666;
        ">
            📎 Adjuntamos un documento PDF con el detalle completo y diseño oficial de tus boletas.
        </p>

        <div style="
            margin-top:20px;
            padding:15px;
            background:#fff7ed;
            border-left:4px solid #f59e0b;
            font-size:13px;
        ">
            ⚠️ Este correo es informativo.
            Tus boletas se encuentran registradas oficialmente en el sistema de sorteos de COOPSERP.
        </div>

    </div>

    {{-- FOOTER --}}
    <div style="
        background:#f8fafc;
        padding:20px;
        text-align:center;
    ">

        <table width="100%">
            <tr>

                <td align="center">
                    <img
                        src="{{ $message->embed(public_path('storage/logos/Coopserp.png')) }}"
                        style="max-height:55px;"
                    >
                </td>

                <td align="center">
                    <img
                        src="{{ $message->embed(public_path('storage/logos/coljuegos.png')) }}"
                        style="max-height:55px;"
                    >
                </td>

            </tr>
        </table>

        <div style="
            margin-top:15px;
            font-size:12px;
            color:#888;
        ">
            Sistema de Gestión de Sorteos © {{ date('Y') }}
            <br>
            Cooperativa COOPSERP
        </div>

    </div>

</div>

</body>
</html>
