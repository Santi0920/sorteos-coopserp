<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletas</title>
</head>

<body style="margin:0; padding:20px; background:#eef2f5; font-family:Arial, Helvetica, sans-serif;">

@foreach($boletas as $boleta)

@php
    $premioUno = $premios->where('orden', 1)->first();
    $premioDos = $premios->where('orden', 2)->first();
@endphp

<div style="
    width:720px;
    margin:0 auto 30px auto;
    background:#f8fbfa;
    border:2px solid #2e7d5b;
    border-radius:12px;
    overflow:hidden;
">

    <!-- HEADER -->
    <table width="100%" style="padding:10px 15px;">
        <tr>
            <td style="width:90px;">
                <img src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
                     style="max-height:65px;">
            </td>

            <td style="text-align:center;">
                <div style="font-size:26px; font-weight:900; color:#2e7d5b;">
                    SORTEOS MOTOCICLETAS AKT
                </div>

            </td>

            <td style="width:90px; text-align:right;">
                <img src="https://uiaf.gov.co/sites/default/files/styles/large/public/inline-images/coljuegos-logo_0.png"
                     style="max-height:65px;">
            </td>
        </tr>
    </table>

    <!-- PREMIOS -->
    <table width="100%" style="padding:10px;">
        <tr>

            <!-- PREMIO 1 -->
            <td style="width:50%; padding:8px;">
                <div style="
                    background:white;
                    border-radius:12px;
                    padding:12px;
                    text-align:center;
                    border:1px solid #ddd;
                ">
                    @if($premioUno?->imagen)
                        <img src="{{ url('storage/'.$premioUno->imagen) }}"
                             style="max-height:110px; margin-bottom:8px;">
                    @endif

                    <div style="font-weight:800; font-size:15px;">
                        Primer Sorteo:
                    </div>

                    <div style="font-size:13px;">
                        Lunes
                        <span style="
                            background:#caa84b;
                            color:white;
                            padding:2px 6px;
                            border-radius:4px;
                            font-weight:700;
                        ">
                            31 de agosto
                        </span>
                        del 2026
                    </div>

                    <div style="margin-top:6px;">Premio:</div>

                    <div style="font-size:18px; font-weight:900;">
                        {{ $premioUno->titulo ?? 'AKT FLEX 123.7 C.C.' }}
                    </div>
                </div>
            </td>

            <!-- PREMIO 2 -->
            <td style="width:50%; padding:8px;">
                <div style="
                    background:white;
                    border-radius:12px;
                    padding:12px;
                    text-align:center;
                    border:1px solid #ddd;
                ">
                    @if($premioDos?->imagen)
                        <img src="{{ url('storage/'.$premioDos->imagen) }}"
                             style="max-height:110px; margin-bottom:8px;">
                    @endif

                    <div style="font-weight:800; font-size:15px;">
                        Segundo Sorteo:
                    </div>

                    <div style="font-size:13px;">
                        Lunes
                        <span style="
                            background:#caa84b;
                            color:white;
                            padding:2px 6px;
                            border-radius:4px;
                            font-weight:700;
                        ">
                            28 de septiembre
                        </span>
                        del 2026
                    </div>

                    <div style="margin-top:6px;">Premio:</div>

                    <div style="font-size:18px; font-weight:900;">
                        {{ $premioDos->titulo ?? 'AKT CR4 150 CBS' }}
                    </div>
                </div>
            </td>

        </tr>
    </table>

    <!-- BARRA VERDE -->
    <table width="100%" style="
        background:#1f7a5a;
        color:white;
        padding:10px 15px;
    ">
        <tr>
            <td style="font-weight:700; font-size:15px;">
                Boleta No.
                <span style="
                    background:#caa84b;
                    padding:3px 14px;
                    font-size:22px;
                    font-weight:900;
                    border-radius:6px;
                    margin-left:8px;
                    letter-spacing:3px;
                ">
                    {{ str_pad($boleta->numero_boleta, 4, '0', STR_PAD_LEFT) }}
                </span>
            </td>

            <td style="text-align:right; font-size:13px; Font-weight:1000;">
                Fecha Emisión: {{ now()->format('d-M-Y') }}
                &nbsp;&nbsp;
                Hora: {{ now()->format('H:i:s') }}
                &nbsp;&nbsp;
                Agencia: {{ $credito->asociado?->agencia ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- DESCRIPCIÓN -->
    <div style="
        padding:15px 20px;
        font-size:12px;
        text-align:center;
        line-height:1.6;
    ">
        <div style="font-weight:900; margin-bottom:5px;">
            DESCRIPCIÓN DE LA ACTIVIDAD:
        </div>

        Aplican Términos y Condiciones del juego promocional, publicados en www.coopserp.com.<br>
        Esta boleta es intransferible, no se podrá vender, ceder, permutar ni endosar.<br>
        Participa en los sorteos del 31 de agosto y 28 de septiembre de 2026.<br>
        Consulte el ganador en www.coopserp.com.<br><br>

        <strong>
            Autoriza COLJUEGOS, Resolución No. 2026XXXXXX del XX del mes XXXX del 2026.
        </strong>
    </div>

</div>

@endforeach

</body>
</html>