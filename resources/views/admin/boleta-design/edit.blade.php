@extends('layouts.admin')

@section('content')

<div class="content-card card">

    <div class="card-header">
        <h5>🎟️ Diseño de Boleta</h5>
        <small class="text-muted">
            Edita el contenido y visualiza exactamente cómo se imprimirá el ticket PDF
        </small>
    </div>

    <div class="card-body">

        <form method="POST"
              action="{{ route('admin.boleta.design.update', $sorteo->id) }}">

            @csrf

            <div class="row g-4">

                {{-- ===================== --}}
                {{-- FORMULARIO --}}
                {{-- ===================== --}}
                <div class="col-md-6">

                    <div class="p-3 border rounded mb-3">
                        <h6>🧾 Título del ticket</h6>
                        <input type="text"
                               id="subtitulo"
                               name="subtitulo"
                               class="form-control"
                               value="{{ $sorteo->design->subtitulo ?? '' }}"
                               placeholder="Ej: Sorteo Especial de Fin de Año">
                    </div>

                    <div class="p-3 border rounded mb-3">
                        <h6>📜 Términos</h6>
                        <textarea id="terminos"
                                  name="terminos"
                                  class="form-control"
                                  rows="5" placeholder="Términos y condiciones...">{{ $sorteo->design->terminos ?? '' }}</textarea>
                    </div>

                    <div class="p-3 border rounded mb-3">
                        <h6>🔎 URL consulta ganador</h6>
                        <input type="text"
                               id="url"
                               name="url_consulta_ganador"
                               class="form-control"
                               value="{{ $sorteo->design->url_consulta_ganador ?? '' }}"
                               placeholder="Ej: https://coopserp.com/consulta-ganador">
                    </div>

                    <div class="p-3 border rounded mb-3">
                        <h6>⚖️ Autorización Coljuegos</h6>
                        <textarea id="legal"
                                  name="texto_coljuegos"
                                  class="form-control"
                                  rows="4" placeholder="Autorización Coljuegos...">{{ $sorteo->design->texto_coljuegos ?? '' }}</textarea>
                    </div>

                    <button class="btn btn-primary w-100">
                        💾 Guardar diseño
                    </button>

                </div>

                {{-- ===================== --}}
                {{-- PREVIEW EXACTO --}}
                {{-- ===================== --}}
                <div class="col-md-6">

                    <h6 class="mb-3">👀 Vista previa del ticket</h6>

                    <div class="border rounded p-3 bg-white shadow-sm">

                        <style>
                            .preview-ticket {
                                font-family: DejaVu Sans, sans-serif;
                                font-size: 12px;
                                color: #111;
                            }

                            .preview-ticket .boleta-numero {
                                font-size: 16px;
                                font-weight: bold;
                            }

                            .preview-ticket .numero-grande {
                                font-size: 20px;
                                font-weight: 900;
                                color: red;
                            }

                            .preview-ticket .center {
                                text-align: center;
                            }

                            .preview-ticket .right {
                                text-align: right;
                            }

                            .preview-ticket .subtitle {
                                font-size: 18px;
                                font-weight: bold;
                            }

                            .preview-ticket .section {
                                margin-top: 15px;
                            }

                            .preview-ticket ul {
                                padding-left: 18px;
                                margin-top: 10px;
                            }

                            .preview-ticket li {
                                margin-bottom: 6px;
                                line-height: 1.3;
                            }

                            .preview-ticket .footer {
                                margin-top: 25px;
                            }

                            .preview-ticket .logo-box {
                                border: 1px solid black;
                                height: 65px;
                                text-align: center;
                                line-height: 65px;
                            }

                            .preview-ticket .footer-logo {
                                max-height: 55px;
                            }

                            .preview-ticket .red {
                                color: red;
                                font-weight: bold;
                            }
                        </style>

                        <div class="preview-ticket p-2">

                            {{-- HEADER --}}
                            <table width="100%">
                                <tr>
                                    <td class="right">

                                        <div class="boleta-numero">
                                            Boleta No.
                                            <span class="numero-grande">000123</span>
                                        </div>

                                        <br>

                                        Fecha Emisión:
                                        <span class="red">{{ now()->format('d-M-Y') }}</span>

                                        <br>

                                        Agencia:
                                        <span class="red">{{ $asociado->agencia ?? 'AGENCIA' }}</span>

                                        <br>

                                        Hora:
                                        <span class="red">{{ now()->format('H:i:s') }}</span>

                                    </td>
                                </tr>
                            </table>

                            <hr>

                            {{-- TITULO --}}
                            <div class="center subtitle" id="preview-subtitulo">
                                {{ $sorteo->design->subtitulo ?? 'Título del sorteo' }}
                            </div>

                            {{-- DESCRIPCION --}}
                            <div class="center section">
                                {{ $sorteo->design->descripcion ?? 'Descripción del sorteo' }}
                            </div>

                            {{-- LOTERIA --}}
                            <div class="center section">
                                Juega con el premio mayor de la lotería de
                                <b>{{ $sorteo->loteria }}</b>
                            </div>

                            {{-- PREMIOS --}}
                            <ul>
                     
                                    <li>
                                        <b>Sorteo:</b>
                                        {{ $sorteo->fecha_sorteo->format('d-M-Y') }}
                                        /
                                        Premio (Ejemplo)
                                    </li>
                           
                            </ul>

                            {{-- TERMINOS --}}
                            <div class="section">
                                <b>DESCRIPCIÓN DE LA ACTIVIDAD</b>
                            </div>

                            <ul>
                                <li id="preview-terminos">
                                    {{ $sorteo->design->terminos ?? 'Términos del sorteo...' }}
                                </li>

                                <li>
                                    Esta boleta es intransferible, no podrá venderse, cederse, permutarse ni endosarse.
                                </li>

                                <li id="preview-url">
                                    {{ $sorteo->design->url_consulta_ganador ?? 'URL consulta ganador' }}
                                </li>
                            </ul>

                            {{-- FOOTER --}}
                            <div class="center footer red" id="preview-legal">
                                {{ $sorteo->design->texto_coljuegos ?? 'Texto legal obligatorio' }}
                            </div>

                            <table width="100%" class="footer">
                                <tr>
                                    <td>
                                        <div class="logo-box">
                                            <img class="footer-logo"
                                                 src="{{ asset('storage/logos/Coopserp.png') }}">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="logo-box">
                                            <img class="footer-logo"
                                                 src="{{ asset('storage/logos/coljuegos.png') }}">
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>

{{-- ===================== --}}
{{-- LIVE UPDATE JS --}}
{{-- ===================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const subtitulo = document.getElementById('subtitulo');
    const terminos = document.getElementById('terminos');
    const url = document.getElementById('url');
    const legal = document.getElementById('legal');

    subtitulo.addEventListener('input', () => {
        document.getElementById('preview-subtitulo').innerText =
            subtitulo.value || 'Título del sorteo';
    });

    terminos.addEventListener('input', () => {
        document.getElementById('preview-terminos').innerText =
            terminos.value || 'Términos del sorteo...';
    });

    url.addEventListener('input', () => {
        document.getElementById('preview-url').innerText =
            url.value || 'URL consulta ganador';
    });

    legal.addEventListener('input', () => {
        document.getElementById('preview-legal').innerText =
            legal.value || 'Texto legal obligatorio';
    });

});
</script>

@endsection