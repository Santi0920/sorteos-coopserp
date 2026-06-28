<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans;
            color: #111;
            font-size: 12px;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .ticket {
            border: 2px solid black;
            border-radius: 8px;
            padding: 25px;
        }

        .boleta-numero {
            font-size: 16px;
            font-weight: bold;
        }

        .numero-grande {
            font-size: 20px;
            font-weight: 900;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .red {
            color: red;
            font-weight: bold;
        }

        .subtitle {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 8px;
        }

        .section {
            margin-top: 8px;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 28px;
        }

        ul {
            margin-top: 8px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .footer {
            margin-top: 80px;
        }

        .logo-box {
            border: 1px solid black;
            height: 65px;
            text-align: center;
            line-height: 65px;
        }

        .footer-logo {
            max-height: 55px;
            vertical-align: middle;
        }

        .top-info {
            line-height: 2;
        }
    </style>
</head>

<body>

@foreach($boletas as $boleta)

    @php
        $sorteo = $boleta->sorteo;

    
        $participacion = ($participacionesPorSorteo ?? collect())->get($sorteo->id);

        $cuenta = data_get($participacion, 'pivot.cuenta') ?: '';
        $agencia = data_get($participacion, 'pivot.agencia') ?: '';

     
        $premiosActuales = isset($premiosPorSorteo)
            ? $premiosPorSorteo->get($sorteo->id, collect())
            : ($premios ?? collect());

    
        $designActual = $sorteo->design ?? ($design ?? null);

        $maxDigits = strlen((string) $sorteo->numero_fin);

        $numero = str_pad(
            $boleta->numero_boleta,
            $maxDigits,
            '0',
            STR_PAD_LEFT
        );
    @endphp

    <div class="page">

        <div class="ticket">

            <table width="100%">
                <tr>
                    <td width="100%" class="right">

                        <div class="boleta-numero">
                            Boleta No.
                            <span class="red numero-grande">{{ $numero }}</span>
                        </div>

                        <br>

                        <div class="top-info">
                            Fecha Emisión:
                            <span class="red">
                                {{ optional($boleta->created_at)->format('d-M-Y') }}
                            </span>

                            Nombre:
                            <span class="red">
                                {{ $asociado->nombres }} {{ $asociado->apellidos }}
                            </span>

                            Cédula:
                            <span class="red">
                                {{ $asociado->documento }}
                            </span>

                            Cuenta:
                            <span class="red">
                                {{ $cuenta }}
                            </span>



                            Agencia:
                            <span class="red">
                                {{ $agencia }}
                            </span>

                            Hora:
                            <span class="red">
                                {{ optional($boleta->created_at)->format('H:i:s') }}
                            </span>
                        </div>

                    </td>
                </tr>
            </table>

            @if($designActual?->subtitulo)
                <div class="center subtitle">
                    {{ $designActual->subtitulo }}
                </div>
            @endif

            @if($designActual?->descripcion)
                <div class="center section">
                    {{ $designActual->descripcion }}
                </div>
            @endif

            @if($premiosActuales->count())
                <ul>
                    @foreach($premiosActuales as $premio)
                        <li>
                            <b>Sorteo:</b>
                            {{ optional($sorteo->fecha_sorteo)->format('d-M-Y') }}
                            /
                            {{ $premio->titulo }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="section section-title">
                DESCRIPCIÓN DE LA ACTIVIDAD
            </div>

            <ul>

                @if($designActual?->terminos)
                    <li>
                        {!! nl2br(e($designActual->terminos)) !!}
                    </li>
                @endif

                <li>
                    Esta boleta es intransferible,
                    no podrá venderse, cederse, permutarse ni endosarse.
                </li>

                @if($designActual?->url_consulta_ganador)
                    <li>
                        Consulta ganador:
                        {{ $designActual->url_consulta_ganador }}
                    </li>
                @endif

            </ul>

            @if($designActual?->texto_coljuegos)
                <div class="center footer red">
                    {{ $designActual->texto_coljuegos }}
                </div>
            @endif

            <table width="100%" class="footer">
                <tr>
                    <td width="50%">
                        <div class="logo-box">
                            <img
                                class="footer-logo"
                                src="{{ public_path('storage/logos/Coopserp.png') }}"
                            >
                        </div>
                    </td>

                    <td width="50%">
                        <div class="logo-box">
                            <img
                                class="footer-logo"
                                src="{{ public_path('storage/logos/coljuegos.png') }}"
                            >
                        </div>
                    </td>
                </tr>
            </table>

        </div>

    </div>

@endforeach

</body>

</html>