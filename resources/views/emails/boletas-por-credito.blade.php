<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletas del Sorteo</title>
</head>

<body style="margin:0; padding:20px; background:#f0f4f8; font-family:Arial, Helvetica, sans-serif;">

@foreach($boletas as $boleta)
<div style="
    max-width:700px;
    margin:0 auto 40px auto;
    background:linear-gradient(160deg, #e8f0ee 0%, #d6e8e2 100%);
    border:2px solid #2d7a5f;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(0,0,0,0.15);
">

    <!-- HEADER LOGOS + TITULO -->
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:16px 20px 10px 20px;">
        <tr>
            <td align="left" style="width:80px; vertical-align:middle;">
                <img src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
                     style="max-height:55px; max-width:75px; object-fit:contain;">
            </td>
            <td align="center" style="vertical-align:middle; padding:0 10px;">
                <div style="font-size:24px; font-weight:900; color:#1a5c40; letter-spacing:1px; text-transform:uppercase;">
                    SORTEOS MOTOCICLETAS AKT
                </div>
                <div style="font-size:11px; color:#2d5a45; font-weight:600; margin-top:4px;">
                    Juega con el premio mayor de la lotería de Cundinamarca en las siguientes fechas:
                </div>
            </td>
            <td align="right" style="width:80px; vertical-align:middle;">
                <img src="https://uiaf.gov.co/sites/default/files/styles/large/public/inline-images/coljuegos-logo_0.png"
                     style="max-height:55px; max-width:75px; object-fit:contain;">
            </td>
        </tr>
    </table>

    <!-- PREMIOS -->
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:8px 16px 12px 16px;">
        <tr>
            @php
                $premioUno = $premios->where('orden', 1)->first();
                $premioDos = $premios->where('orden', 2)->first();
            @endphp

            <!-- PRIMER SORTEO -->
            <td style="width:50%; padding:6px; vertical-align:middle;">
                <div style="background:rgba(255,255,255,0.65); border:1px solid #a8d5c2; border-radius:12px; padding:12px; text-align:center;">
                    @if($premioUno && $premioUno->imagen)
                        <img src="{{ url('storage/' . $premioUno->imagen) }}"
                            style="max-height:95px; max-width:175px; object-fit:contain; display:block; margin:0 auto 8px auto;">
                    @else
                        <div style="font-size:50px; margin-bottom:8px;">🏍️</div>
                    @endif
                    <div style="font-size:13px; font-weight:700; color:#1a1a1a;">Primer Sorteo:</div>
                    <div style="font-size:12px; color:#1a1a1a; margin:3px 0;">
                        Lunes <span style="background:#c8a84b; color:white; border-radius:4px; padding:1px 6px; font-weight:700;">31 de agosto</span> del 2026.
                    </div>
                    <div style="font-size:11px; color:#555; margin-top:4px;">Premio:</div>
                    <div style="font-size:15px; font-weight:900; color:#0f2e1e; line-height:1.2;">
                        {{ $premioUno?->titulo ?? 'AKT FLEX 123.7 C.C.' }}
                    </div>
                </div>
            </td>

            <!-- SEGUNDO SORTEO -->
            <td style="width:50%; padding:6px; vertical-align:middle;">
                <div style="background:rgba(255,255,255,0.65); border:1px solid #a8d5c2; border-radius:12px; padding:12px; text-align:center;">
                    @if($premioDos && $premioDos->imagen)
                        <img src="{{ url('storage/' . $premioDos->imagen) }}"
                            style="max-height:95px; max-width:175px; object-fit:contain; display:block; margin:0 auto 8px auto;">
                    @else
                        <div style="font-size:50px; margin-bottom:8px;">🏍️</div>
                    @endif

                    <div style="font-size:13px; font-weight:700; color:#1a1a1a;">Segundo Sorteo:</div>
                    <div style="font-size:12px; color:#1a1a1a; margin:3px 0;">
                        Lunes <span style="background:#c8a84b; color:white; border-radius:4px; padding:1px 6px; font-weight:700;">28 de septiembre</span> del 2026.
                    </div>
                    <div style="font-size:11px; color:#555; margin-top:4px;">Premio:</div>
                    <div style="font-size:15px; font-weight:900; color:#0f2e1e; line-height:1.2;">
                        {{ $premioDos?->titulo ?? 'AKT CR4 150 CBS.' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- BARRA BOLETA -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="background:linear-gradient(90deg, #1a6b48, #2d9c6a); padding:10px 20px;">
        <tr>
            <td style="color:white; font-size:14px; font-weight:700; white-space:nowrap; vertical-align:middle;">
                Boleta No.
                <span style="
                    background:#c8a84b;
                    color:white;
                    font-size:20px;
                    font-weight:900;
                    padding:2px 14px;
                    border-radius:6px;
                    margin-left:6px;
                    letter-spacing:3px;
                ">{{ str_pad($boleta->numero_boleta, 4, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td style="color:white; font-size:11px; text-align:right; vertical-align:middle;">
                📅 Fecha: {{ now()->format('d-M-Y') }} &nbsp;|&nbsp;
                🕐 {{ now()->format('H:i:s') }} &nbsp;|&nbsp;
                🏢 {{ $credito->asociado?->agencia ?? '—' }}
            </td>
        </tr>
    </table>

    <!-- DESCRIPCION -->
    <div style="padding:14px 24px 20px 24px; text-align:center; font-size:11.5px; color:#1a1a1a; line-height:1.75;">
        <div style="font-weight:900; font-size:12px; margin-bottom:6px;">DESCRIPCIÓN DE LA ACTIVIDAD:</div>
        Aplican Términos y Condiciones del juego promocional, los cuales se encuentran publicados en la página web www.coopserp.com.<br>
        Esta boleta es intransferible, no se podrá vender, ceder, permutar ni endosar.<br>
        Esta boleta le permite participar en los sorteos de las fechas Lunes 31 de agosto del 2026 y Lunes 28 de septiembre del 2026.<br>
        Podrá consultar el asociado ganador en la página web www.coopserp.com.<br><br>
        <em>Autoriza COLJUEGOS, Resolución No. 2026XXXXXX del XX del mes XXXXX del 2026.</em>
    </div>

</div>
@endforeach

</body>
</html>