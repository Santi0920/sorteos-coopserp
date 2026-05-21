<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        .boleta-numero {
            font-size: 16px;
            font-weight: bold;
        }

        .numero-grande {
            font-size: 20px;
            font-weight: 900;

        }
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

        .line {
            border-top: 2px solid black;
            margin-bottom: 20px;
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
        }



        .section-title {
            font-size: 18px;
            font-weight: bold;
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
            margin-top: 40px;
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
    </style>
</head>

<body>

@foreach($boletas as $boleta)

    @php
        $sorteo = $boleta->sorteo;

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
                    <td width="50%" class="right">

                        <div class="boleta-numero">
                            Boleta No.
                            <span class="red numero-grande">{{ $numero }}</span>
                        </div>

                        <br>

                        Fecha Emisión:
                        <span class="red">{{ $boleta->created_at->format('d-M-Y') }}</span>

                        Agencia:
                        <span class="red">{{ $asociado->agencia }}</span>

                        Hora:
                        <span class="red">{{ $boleta->created_at->format('H:i:s') }}</span>

                    </td>
                </tr>
            </table>

            <div class="center subtitle">
                {{ $design?->subtitulo }}
            </div>

            <div class="center section">
                {{ $design?->descripcion }}
            </div>

            <div class="center section">
                Juega con el premio mayor de la lotería de
                <b>{{ $sorteo->loteria }}</b>
                en las siguientes fechas:
            </div>

            <ul>
                @foreach($premios as $index => $premio)
                    <li>
                        <b>
                            Sorteo:
                        </b>
                        {{ $sorteo->fecha_sorteo->format('d-M-Y') }}
                        /
                        {{ $premio->titulo }}
                    </li>
                @endforeach
            </ul>

            <div class="section section-title">
                DESCRIPCIÓN DE LA ACTIVIDAD
            </div>

            <ul>

                @if($design?->terminos)
                    <li>
                        {!! nl2br(e($design->terminos)) !!}
                    </li>
                @endif

                <li>
                    Esta boleta es intransferible,
                    no podrá venderse, cederse, permutarse ni endosarse.
                </li>

                @if($design?->url_consulta_ganador)
                    <li>
                        Consulta ganador:
                        {{ $design->url_consulta_ganador }}
                    </li>
                @endif

            </ul>

            <div class="center footer red">
                {{ $design?->texto_coljuegos }}
            </div>

            <table width="100%" class="footer">
                <tr>
                    <td>
                        <div class="logo-box">
                                <img
                                    class="footer-logo"
                                    src="file://{{ public_path('storage/logos/Coopserp.png') }}"
                                >
                        </div>
                    </td>

                    <td>
                        <div class="logo-box">
                                <img
                                    class="footer-logo"
                                    src="file://{{ public_path('storage/logos/coljuegos.png') }}"
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