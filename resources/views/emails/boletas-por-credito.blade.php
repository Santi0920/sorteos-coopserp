<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletas del Sorteo</title>
</head>

<body style="margin:0; padding:30px; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#111827;">

<div style="max-width:760px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:20px; overflow:hidden;">

    <!-- HEADER -->
    <div style="background:linear-gradient(135deg, #0f172a, #1d4ed8); color:white; padding:32px;">
        <div style="font-size:26px; font-weight:700; margin-bottom:8px;">
            Sistema de Sorteos COOPSERP
        </div>
        <div style="opacity:.9; font-size:14px;">
            Boletas asignadas generadas automáticamente por el sistema
        </div>
    </div>

    <!-- CONTENIDO -->
    <div style="padding:28px;">

        <!-- INFO ASOCIADO -->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
            <tr>
                <td style="padding:8px;">
                    <div style="font-size:12px; color:#64748b;">Asociado</div>
                    <div style="font-size:15px; font-weight:700;">
                        {{ $credito->asociado?->nombre_completo ?? '—' }}
                    </div>
                </td>

                <td style="padding:8px;">
                    <div style="font-size:12px; color:#64748b;">Documento</div>
                    <div style="font-size:15px; font-weight:700;">
                        {{ $credito->asociado?->documento ?? '—' }}
                    </div>
                </td>
            </tr>

            <tr>
                <td style="padding:8px;">
                    <div style="font-size:12px; color:#64748b;">Fecha emisión</div>
                    <div style="font-size:15px; font-weight:700; color:#1d4ed8;">
                        {{ now()->format('d/m/Y H:i:s') }}
                    </div>
                </td>

                <td style="padding:8px;">
                    <div style="font-size:12px; color:#64748b;">Cantidad de boletas</div>
                    <div style="font-size:15px; font-weight:700;">
                        {{ $boletas->count() }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- TITULO -->
        <div style="margin:18px 0; font-size:16px; font-weight:700; color:#0f172a;">
            Boletas asignadas
        </div>

        <!-- BOLETAS -->
        <div style="text-align:left; margin-bottom:25px;">
            @foreach($boletas as $boleta)
                <span style="
                    display:inline-block;
                    background:#dbeafe;
                    color:#1d4ed8;
                    font-weight:700;
                    font-size:13px;
                    padding:7px 12px;
                    border-radius:999px;
                    margin:3px;
                    letter-spacing:1px;
                ">
                    {{ $boleta->numero_boleta }}
                </span>
            @endforeach
        </div>

        <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;">

        <!-- INFO SORTEO -->
        <div style="font-size:14px; line-height:1.7; color:#475569;">
            <p style="margin:0 0 10px 0; font-weight:700; color:#0f172a;">
                Información del sorteo
            </p>

            <p style="margin:0;">
                Sorteos de motocicletas AKT con premios oficiales de la lotería de Cundinamarca.
            </p>

            <ul style="margin-top:10px; padding-left:18px;">
                <li>Primer Sorteo: 31 de agosto 2026 - AKT FLEX 123.7 C.C.</li>
                <li>Segundo Sorteo: 28 de septiembre 2026 - AKT CR4 150 CBS</li>
            </ul>
            <div style="margin-top:14px; font-size:13px; line-height:1.7; color:#475569;">

    <p style="margin:0 0 10px 0; font-weight:700; color:#0f172a;">
        DESCRIPCIÓN DE LA ACTIVIDAD:
    </p>

    <ul style="margin:0; padding-left:18px;">
        <li>
            Aplican Términos y Condiciones del juego promocional, los cuales se encuentran publicados en la página web www.coopserp.com.
        </li>
        <li>
            Esta boleta es intransferible, no se podrá vender, ceder, permutar ni endosar.
        </li>
        <li>
            Esta boleta le permite participar en los sorteos de las fechas Lunes 31 de agosto del 2026 y Lunes 28 de septiembre del 2026.
        </li>
        <li>
            Podrá consultar el asociado ganador en la página web www.coopserp.com.
        </li>
    </ul>

    <div style="margin-top:12px; font-size:12px; color:#64748b;">
        Autoriza COLJUEGOS, Resolución No. 2026XXXXXX del XX del mes XXXXX del 2026.
    </div>

</div>
        </div>

        <!-- FOOTER LOGOS -->
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:40px;">
            <tr>
                <!-- IZQUIERDA -->
                <td align="left" style="width:50%; padding:10px;">
                    <img src="https://www.coopserp.com/wp/wp-content/uploads/2024/04/Logo-grande-Coopserp-2019-e1721607356635.png"
                         style="max-height:60px;">
                </td>

                <!-- DERECHA -->
                <td align="right" style="width:50%; padding:10px;">
                    <img src="https://uiaf.gov.co/sites/default/files/styles/large/public/inline-images/coljuegos-logo_0.png"
                         style="max-height:60px;">
                </td>
            </tr>
        </table>

        <!-- FOOTER LEGAL -->
        <div style="text-align:center; font-size:12px; color:#94a3b8; margin-top:10px;">
            Documento interno del sistema de sorteos autorizado por COLJUEGOS
        </div>

    </div>
</div>

</body>
</html>


<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletas asignadas</title>
</head>
<body style="margin:0; padding:30px; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <div style="max-width:760px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:20px; overflow:hidden;">
        <div style="background:linear-gradient(135deg, #0f172a, #1d4ed8); color:white; padding:32px;">
            <div style="font-size:28px; font-weight:700; margin-bottom:8px;">
                Boletas asignadas para el sorteo
            </div>
            <div style="opacity:.88; font-size:15px;">
                Hola {{ $credito->asociado?->nombre_completo ?? 'Asociado' }}, estas son tus boletas generadas por el crédito registrado.
            </div>
        </div>

        <div style="padding:28px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                <tr>
                    <td style="padding:0 0 14px 0;">
                        <div style="font-size:12px; color:#64748b;">Asociado</div>
                        <div style="font-size:15px; font-weight:700;">{{ $credito->asociado?->nombre_completo ?? '—' }}</div>
                    </td>
                    <td style="padding:0 0 14px 0;">
                        <div style="font-size:12px; color:#64748b;">Documento</div>
                        <div style="font-size:15px; font-weight:700;">{{ $credito->asociado?->documento ?? '—' }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 0 14px 0;">
                        <div style="font-size:12px; color:#64748b;">Crédito</div>
                        <div style="font-size:15px; font-weight:700;">{{ $credito->numero_credito }}</div>
                    </td>
                    <td style="padding:0 0 14px 0;">
                        <div style="font-size:12px; color:#64748b;">Monto del crédito</div>
                        <div style="font-size:15px; font-weight:700;">${{ number_format((float)$credito->monto, 0, ',', '.') }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 0 14px 0;">
                        <div style="font-size:12px; color:#64748b;">Sorteo</div>
                        <div style="font-size:15px; font-weight:700;">{{ $sorteo->nombre }}</div>
                    </td>
                    <td style="padding:0 0 14px 0;">
                        <div style="font-size:12px; color:#64748b;">Fecha del sorteo</div>
                        <div style="font-size:15px; font-weight:700;">{{ $sorteo->fecha_sorteo->format('d/m/Y') }}</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0;">
                        <div style="font-size:12px; color:#64748b;">Lotería</div>
                        <div style="font-size:15px; font-weight:700;">{{ $sorteo->loteria ?: 'Por definir' }}</div>
                    </td>
                    <td style="padding:0;">
                        <div style="font-size:12px; color:#64748b;">Cantidad de boletas</div>
                        <div style="font-size:15px; font-weight:700;">{{ $boletas->count() }}</div>
                    </td>
                </tr>
            </table>

            <div style="margin-bottom:16px; font-size:16px; font-weight:700; color:#0f172a;">
                Tus boletas asignadas
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; border:1px solid #e5e7eb;">
                <thead>
                    <tr style="background:#eff6ff;">
                        <th align="left" style="padding:12px; border-bottom:1px solid #e5e7eb; color:#1d4ed8; font-size:12px;">Número de boleta</th>
                        <th align="left" style="padding:12px; border-bottom:1px solid #e5e7eb; color:#1d4ed8; font-size:12px;">Monto base</th>
                        <th align="left" style="padding:12px; border-bottom:1px solid #e5e7eb; color:#1d4ed8; font-size:12px;">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($boletas as $boleta)
                        <tr>
                            <td style="padding:12px; border-bottom:1px solid #e5e7eb;">
                                <span style="display:inline-block; padding:7px 12px; border-radius:999px; background:#dbeafe; color:#1d4ed8; font-weight:700; letter-spacing:1px;">
                                    {{ $boleta->numero_boleta }}
                                </span>
                            </td>
                            <td style="padding:12px; border-bottom:1px solid #e5e7eb;">
                                ${{ number_format((float)$boleta->monto_base, 0, ',', '.') }}
                            </td>
                            <td style="padding:12px; border-bottom:1px solid #e5e7eb;">
                                Activa
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:22px; font-size:14px; color:#475569; line-height:1.7;">
                Estas boletas fueron generadas automáticamente con base en el crédito registrado y la configuración vigente del sistema de sorteos.
            </div>
        </div>
    </div>
</body>
</html> -->