<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Hoja de Servicio Técnico - Tablero Eléctrico</title>
    <style>
        @page {
            margin: 5mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 5px;
            line-height: 1.2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 1px 3px;
            text-align: left;
            font-size: 9.5px;
        }

        th {
            background-color: #d8d8d8;
            font-weight: bold;
        }

        .header-table td {
            vertical-align: top;
        }

        .logo-cell {
            width: 18%;
            text-align: center;
        }

        .title-cell {
            width: 60%;
            text-align: center;
            vertical-align: middle;
        }

        .code-cell {
            width: 22%;
            text-align: center;
        }

        .checkbox-small {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            text-align: center;
            line-height: 8px;
            font-size: 9px;
            margin: 0 0px;
        }

        .firma-box {
            min-height: 40px;
            text-align: center;
        }

        .footer-text {
            font-size: 6px;
            text-align: center;
            margin-top: 3px;
        }

        .rich-text {
            max-height: 130px;
            overflow: hidden;
        }

        .rich-text p {
            margin: 0;
            padding: 0;
            line-height: 1.3;
        }

        .rich-text ul,
        .rich-text ol {
            margin: 2px 0;
            padding-left: 15px;
        }

        .rich-text li {
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }

        .rich-text br {
            content: "";
            display: block;
            margin: 1px 0;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('logo_empresa.png') }}" alt="Logo" style="max-width: 70px;">
                <div style="font-size: 6px; font-weight: bold; margin-top: 2px;">SOLUCIONES DE<br>ENERGÍA</div>
                <div style="font-size: 5px;">V.I.P. SAS EN BIS</div>
            </td>
            <td class="title-cell">
                <div style="font-size: 11px; font-weight: bold;">HOJA DE SERVICIO TÉCNICO PARA TABLEROS ELÉCTRICOS</div>
                <div style="font-size: 9px; margin-top: 2px;">
                    EMPRESA: {{ $solicitud->client->enterprise_name }}<br>
                    SUCURSAL: {{ $solicitud->sucursal->name }}
                </div>
            </td>
            <td class="code-cell">
                <div style="font-size: 11px; font-weight: bold;">
                    ORDEN No.<br>
                    {{ $solicitud->numero_orden }}
                </div>
            </td>
        </tr>
    </table>

    <!-- FECHA -->
    <table>
        <tr>
            <th style="width: 8%;">DIA</th>
            <th style="width: 8%;">MES</th>
            <th style="width: 8%;">AÑO</th>
            <th>CIUDAD</th>
        </tr>
        <tr>
            <td>{{ $registro->created_at->format('d') }}</td>
            <td>{{ $registro->created_at->format('m') }}</td>
            <td>{{ $registro->created_at->format('Y') }}</td>
            <td>{{ $solicitud->sucursal->ciudad ?? $solicitud->sucursal->address }}</td>
        </tr>
    </table>

    <!-- DATOS DEL CLIENTE -->
    <table>
        <tr>
            <th colspan="4">DATOS DEL CLIENTE</th>
        </tr>
        <tr>
            <th style="width: 18%;">RAZÓN SOCIAL</th>
            <td colspan="3">{{ $solicitud->client->enterprise_name }}</td>
        </tr>
        <tr>
            <th>SUCURSAL</th>
            <td style="width: 32%;">{{ $solicitud->sucursal->name }}</td>
            <th style="width: 18%;">CONTACTO</th>
            <td>{{ $solicitud->sucursal->contact_name }}</td>
        </tr>
        <tr>
            <th>DIRECCIÓN</th>
            <td>{{ $solicitud->sucursal->address }}</td>
            <th>TELÉFONO</th>
            <td>{{ $solicitud->sucursal->phone_number }}</td>
        </tr>
        <tr>
            <th>E-MAIL</th>
            <td colspan="3">{{ $solicitud->sucursal->email }}</td>
        </tr>
    </table>

    <!-- DATOS DEL EQUIPO -->
    <table>
        <tr>
            <th colspan="6">DATOS DEL EQUIPO</th>
        </tr>
        <tr>
            <th style="width: 16%;">TENSIÓN DE OPERACIÓN</th>
            <td style="width: 17%;">{{ $registro->tension_operacion }}</td>
            <th style="width: 16%;">CORRIENTE NOMINAL</th>
            <td style="width: 17%;">{{ $registro->corriente_nominal }}</td>
            <th style="width: 16%;">ELEMENTO DE MANIOBRA</th>
            <td style="width: 18%;">{{ $registro->elemento_maniobra }}</td>
        </tr>
        <tr>
            <th>FABRICANTE</th>
            <td>{{ $registro->fabricante }}</td>
            <th>TIPO DE APLICACIÓN</th>
            <td>{{ $registro->tipo_aplicacion }}</td>
            <th>CONTROL ATS</th>
            <td>{{ $registro->control_ats }}</td>
        </tr>
        <tr>
            <th>TIPO DE SERVICIO</th>
            <td colspan="5">
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Mantenimiento' ? 'X' : '' }}</span> MANTENIMIENTO
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Servicio' ? 'X' : '' }}</span> SERVICIO
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Inspeccion' ? 'X' : '' }}</span> INSPECCIÓN
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Soporte' ? 'X' : '' }}</span> SOPORTE
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Emergencia' ? 'X' : '' }}</span> EMERGENCIA
            </td>
        </tr>
    </table>

    <!-- OBSERVACIONES INICIALES -->
    <table>
        <tr>
            <th>OBSERVACIONES INICIALES</th>
        </tr>
        <tr>
            <td style="min-height: 30px; vertical-align: top;">
                <div class="rich-text">{!! $registro->observaciones_iniciales !!}</div>
            </td>
        </tr>
    </table>

    <!-- CHECK LIST -->
    <table>
        <tr>
            <th colspan="4">CHECK LIST</th>
        </tr>
        <tr>
            <th style="width: 25%;">GABINETE</th>
            <td style="width: 25%;">{{ $registro->gabinete }}</td>
            <th style="width: 25%;">PUERTAS</th>
            <td style="width: 25%;">{{ $registro->puertas }}</td>
        </tr>
        <tr>
            <th>CERRADURAS</th>
            <td>{{ $registro->cerraduras }}</td>
            <th>BISAGRAS</th>
            <td>{{ $registro->bisagras }}</td>
        </tr>
        <tr>
            <th>LIMPIEZA GENERAL</th>
            <td>{{ $registro->limpieza_general }}</td>
            <th>PILOTOS INDICADORES</th>
            <td>{{ $registro->pilotos_indicadores }}</td>
        </tr>
        <tr>
            <th>SELECTORES</th>
            <td>{{ $registro->selectores }}</td>
            <th>RELÉS</th>
            <td>{{ $registro->reles }}</td>
        </tr>
        <tr>
            <th>TEMPORIZADORES</th>
            <td>{{ $registro->temporizadores }}</td>
            <th>CONTACTORES</th>
            <td>{{ $registro->contactores }}</td>
        </tr>
        <tr>
            <th>INTERRUPTORES</th>
            <td>{{ $registro->interruptores }}</td>
            <th>CONEXIONES DE CONTROL</th>
            <td>{{ $registro->conexiones_control }}</td>
        </tr>
        <tr>
            <th>CONEXIONES DE POTENCIA</th>
            <td>{{ $registro->conexiones_potencia }}</td>
            <th>BARRAJE DE POTENCIA</th>
            <td>{{ $registro->barraje_potencia }}</td>
        </tr>
        <tr>
            <th>BARRAJE DE NEUTROS</th>
            <td>{{ $registro->barraje_neutros }}</td>
            <th>BARRAJE DE TIERRAS</th>
            <td>{{ $registro->barraje_tierras }}</td>
        </tr>
        <tr>
            <th>PLC</th>
            <td>{{ $registro->plc }}</td>
            <th>ATS</th>
            <td>{{ $registro->ats }}</td>
        </tr>
        <tr>
            <th>FUENTES AUXILIARES</th>
            <td>{{ $registro->fuentes_auxiliares_check }}</td>
            <th>CAPACITORES</th>
            <td>{{ $registro->capacitores }}</td>
        </tr>
        <tr>
            <th>ANALIZADOR DE RED</th>
            <td colspan="3">{{ $registro->analizador_de_red }}</td>
        </tr>
    </table>

    <!-- ACTIVIDAD REALIZADA -->
    <table>
        <tr>
            <th>ACTIVIDAD REALIZADA</th>
        </tr>
        <tr>
            <td style="min-height: 30px; vertical-align: top;">
                <div class="rich-text">{!! $registro->actividad_realizada !!}</div>
            </td>
        </tr>
    </table>

    <!-- PRUEBAS CON EL EQUIPO EN OPERACIÓN -->
    <table>
        <tr>
            <th colspan="4">PRUEBAS CON EL EQUIPO EN OPERACIÓN - TIEMPOS (Segundos)</th>
        </tr>
        <tr>
            <th style="width: 20%;">TDES</th>
            <td style="width: 30%;">{{ $registro->segundos_tdes }}</td>
            <th style="width: 20%;">TDNE</th>
            <td style="width: 30%;">{{ $registro->segundos_tdne }}</td>
        </tr>
        <tr>
            <th>TDTP</th>
            <td>{{ $registro->segundos_tdtp }}</td>
            <th>TDEN</th>
            <td>{{ $registro->segundos_tden }}</td>
        </tr>
        <tr>
            <th>TDEC</th>
            <td colspan="3">{{ $registro->segundos_tdec }}</td>
        </tr>
    </table>

    <!-- AJUSTES Y TEMPERATURA -->
    <table>
        <tr>
            <th colspan="4" style="background-color: #b8b8b8;">AJUSTES</th>
        </tr>
        <tr>
            <th style="width: 25%;">ALTO VOLTAJE</th>
            <td style="width: 25%;">{{ $registro->alto_voltaje }}</td>
            <th style="width: 25%;">BAJO VOLTAJE</th>
            <td style="width: 25%;">{{ $registro->bajo_voltaje }}</td>
        </tr>
        <tr>
            <th>ALTA FRECUENCIA</th>
            <td>{{ $registro->alta_frecuencia }}</td>
            <th>BAJA FRECUENCIA</th>
            <td>{{ $registro->baja_frecuencia }}</td>
        </tr>
        <tr>
            <th>SOBRE CARGA</th>
            <td>{{ $registro->sobre_carga }}</td>
            <th>SOBRE CORRIENTE</th>
            <td>{{ $registro->sobre_corriente }}</td>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #b8b8b8;">TEMPERATURA</th>
        </tr>
        <tr>
            <th>CABLES DE POTENCIA</th>
            <td>{{ $registro->cables_potencia }}</td>
            <th>TERMINALES</th>
            <td>{{ $registro->terminales }}</td>
        </tr>
        <tr>
            <th>CUERPO DE CONTACTORES</th>
            <td>{{ $registro->cuepo_contactores }}</td>
            <th>CUERPO DE INTERRUPTORES</th>
            <td>{{ $registro->cuerpo_interruptores }}</td>
        </tr>
        <tr>
            <th>TRANSFORMADORES</th>
            <td>{{ $registro->transformadores }}</td>
            <th>PUNTO MÁS CALIENTE</th>
            <td>{{ $registro->punto_mas_caliente }}</td>
        </tr>
    </table>

    <!-- LECTURAS ELÉCTRICAS -->
    <table>
        <tr>
            <th colspan="7" style="background-color: #b8b8b8;">LECTURAS ELÉCTRICAS</th>
        </tr>
        <tr style="background-color: #d8d8d8;">
            <th style="width: 14%;">PARÁMETRO</th>
            <th style="width: 12%;">L1</th>
            <th style="width: 12%;">L2</th>
            <th style="width: 12%;">L3</th>
            <th style="width: 15%;">L1-N</th>
            <th style="width: 15%;">L2-N</th>
            <th style="width: 20%;">L3-N / OTROS</th>
        </tr>
        <tr>
            <th>VOLTAJE</th>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>{{ $registro->l1_n }}</td>
            <td>{{ $registro->l2_n }}</td>
            <td>{{ $registro->l3_n }}</td>
        </tr>
        <tr>
            <th>KW</th>
            <td>{{ $registro->l1_kw }}</td>
            <td>{{ $registro->l2_kw }}</td>
            <td>{{ $registro->l3_kw }}</td>
            <td colspan="3">AVG: {{ $registro->avg_kw }}</td>
        </tr>
        <tr>
            <th>CORRIENTE (A)</th>
            <td>{{ $registro->l1_corriente }}</td>
            <td>{{ $registro->l2_corriente }}</td>
            <td>{{ $registro->l3_corriente }}</td>
            <td colspan="3">-</td>
        </tr>
        <tr>
            <th>KVA</th>
            <td>{{ $registro->l1_kva }}</td>
            <td>{{ $registro->l2_kva }}</td>
            <td>{{ $registro->l3_kva }}</td>
            <td colspan="3">AVG: {{ $registro->avg_kva }}</td>
        </tr>
        <tr>
            <th>FRECUENCIA (HZ)</th>
            <td colspan="6">{{ $registro->hz }}</td>
        </tr>
        <tr>
            <th>FACTOR DE POTENCIA</th>
            <td colspan="6">{{ $registro->pf }}</td>
        </tr>
    </table>

    <!-- OBSERVACIONES Y RECOMENDACIONES -->
    <table>
        <tr>
            <th>OBSERVACIONES DE PRUEBAS</th>
        </tr>
        <tr>
            <td style="min-height: 20px; vertical-align: top;">{{ $registro->observaciones_pruebas }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>RECOMENDACIONES</th>
        </tr>
        <tr>
            <td style="min-height: 20px; vertical-align: top;">{{ $registro->recomendaciones }}</td>
        </tr>
    </table>

    <!-- LLEGADA Y SALIDA TÉCNICO -->
    <table>
        <tr>
            <th colspan="5" style="background-color: #b8b8b8; text-align: center;">LLEGADA TECNICO</th>
        </tr>
        <tr>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">DIA</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">MES</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">AÑO</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">HORA</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">MIN</th>
        </tr>
        <tr>
            <td style="text-align: center; font-size: 8px;">{{ date('d', strtotime($registro->llegada_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('m', strtotime($registro->llegada_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('Y', strtotime($registro->llegada_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('H', strtotime($registro->llegada_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('i', strtotime($registro->llegada_tecnico)) }}</td>
        </tr>
        <tr>
            <th colspan="5" style="background-color: #b8b8b8; text-align: center;">SALIDA TECNICO</th>
        </tr>
        <tr>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">DIA</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">MES</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">AÑO</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">HORA</th>
            <th style="width: 8%; background-color: #d8d8d8; font-size: 8px; text-align: center;">MIN</th>
        </tr>
        <tr>
            <td style="text-align: center; font-size: 8px;">{{ date('d', strtotime($registro->salida_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('m', strtotime($registro->salida_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('Y', strtotime($registro->salida_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('H', strtotime($registro->salida_tecnico)) }}</td>
            <td style="text-align: center; font-size: 8px;">{{ date('i', strtotime($registro->salida_tecnico)) }}</td>
        </tr>
    </table>

    <!-- POSICIÓN DE INSTRUMENTOS Y CALIFICACIÓN -->
    <table>
        <tr>
            <th colspan="4" style="background-color: #b8b8b8;">POSICIÓN DE INSTRUMENTOS AL CONCLUIR EL SERVICIO</th>
            <th colspan="3" style="background-color: #b8b8b8;">CALIFICACIÓN DEL SERVICIO</th>
        </tr>
        <tr style="background-color: #d8d8d8;">
            <th style="width: 30%;"></th>
            <th style="width: 8%; text-align: center;">M</th>
            <th style="width: 8%; text-align: center;">A</th>
            <th style="width: 8%; text-align: center;">OFF</th>
            <td rowspan="4" colspan="3" style="text-align: center;">
                <span class="checkbox-small">{{ $registro->calificacion_servicio == 'Bueno' ? 'X' : '' }}</span> BUENO &nbsp;
                <span class="checkbox-small">{{ $registro->calificacion_servicio == 'Regular' ? 'X' : '' }}</span> REGULAR &nbsp;
                <span class="checkbox-small">{{ $registro->calificacion_servicio == 'Malo' ? 'X' : '' }}</span> MALO
            </td>
        </tr>
        <tr>
            <td>CONTROL</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->control == 'Manual' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->control == 'Auto' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->control == 'Off' ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>SELECTOR</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->selector == 'Manual' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->selector == 'Auto' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->selector == 'Off' ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>FUENTES AUXILIARES</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->fuentes_auxiliares_posicion == 'On' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->fuentes_auxiliares_posicion == 'Off' ? 'X' : '' }}</span></td>
        </tr>
    </table>

    <!-- FIRMAS -->
    <table>
        <tr>
            <th style="width: 50%; background-color: #FF7C61;">Tecnico:</th>
            <th style="width: 50%; background-color: #FF7C61;">Cliente:</th>
        </tr>
        <tr>
            <td class="firma-box">
                @if($registro->firma_tecnico)
                <img src="{{ $registro->firma_tecnico }}" alt="Firma Técnico" style="max-width: 100%; height: 70px;">
                @endif
            </td>
            <td class="firma-box">
                @if($registro->firma_cliente)
                <img src="{{ $registro->firma_cliente }}" alt="Firma Cliente" style="max-width: 100%; height: 70px;">
                @endif
            </td>
        </tr>
        <tr>
            <th>FIRMA:</th>
            <th>FIRMA:</th>
        </tr>
        <tr>
            <td style="font-size: 8px;">
                Nombre: {{ $registro->nombre_tecnico }}<br>
                Cédula: {{ $registro->cedula_tecnico }}
            </td>
            <td style="font-size: 8px;">
                Nombre: {{ $registro->nombre_cliente }}<br>
                Cédula: {{ $registro->cedula_cliente }}
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer-text">
        <strong>Carrera 83 No. 72B 06 Tel: (571) 694 9133 / 694 9128 / 694 9125 www.genservices.com.co Bogotá, D.C. - Colombia</strong><br>
        Las partes interesadas que intervienen en este informe, suscriben el presente
            COMPROMISO DE CONFIDENCIALIDAD Y NO DIVULGACIÓN DE LA INFORMACIÓN,
            que está clasificada como reservada, en la entrega del informe, esta únicamente será
            compartida con las sucursales, empresas y personas autorizadas para el desarrollo del
            servicio. Las partes interesadas cumplirán los requisitos legales y reglamentarios
            relacionados con las política de confidencialidad de la Empresa GEN SERVICES S.A.S.,
            por lo tanto, no se emitirán copias, divulgaciones o reproducciones a tercero,
            contenidas en el sistema.
    </div>

    <!-- SALTO DE PÁGINA -->
    <div style="page-break-after: always;"></div>

    <!-- PÁGINA 2: REGISTRO FOTOGRÁFICO -->
    <!-- HEADER PÁGINA 2 -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('logo_empresa.png') }}" alt="Logo" style="max-width: 70px;">
                <div style="font-size: 6px; font-weight: bold; margin-top: 2px;">SOLUCIONES DE<br>ENERGÍA</div>
                <div style="font-size: 5px;">V.I.P. SAS EN BIS</div>
            </td>
            <td class="title-cell">
                <div style="font-size: 11px; font-weight: bold;">REGISTRO FOTOGRÁFICO - TABLERO ELÉCTRICO</div>
                <div style="font-size: 9px; margin-top: 2px;">
                    EMPRESA: {{ $solicitud->client->enterprise_name }}<br>
                    SUCURSAL: {{ $solicitud->sucursal->name }}
                </div>
            </td>
            <td class="code-cell">
                <div style="font-size: 11px; font-weight: bold;">
                    ORDEN No.<br>
                    {{ $solicitud->numero_orden }}
                </div>
            </td>
        </tr>
    </table>

    <!-- FOTOS ANTES -->
    @if($registro->Foto_uno_antes || $registro->Foto_dos_antes || $registro->Foto_tres_antes)
    <table>
        <tr>
            <th colspan="2" style="background-color: #FF7C61; text-align: center;">FOTOS ESTADO INICIAL</th>
        </tr>
    </table>
    <table style="margin-top: 3px;">
        @php
        $fotosAntes = [
            ['foto' => $registro->Foto_uno_antes, 'alt' => 'Foto 1 Antes'],
            ['foto' => $registro->Foto_dos_antes, 'alt' => 'Foto 2 Antes'],
            ['foto' => $registro->Foto_tres_antes, 'alt' => 'Foto 3 Antes'],
        ];
        $fotosAntesFiltradas = array_filter($fotosAntes, function($item) {
            return !empty($item['foto']);
        });
        $chunks = array_chunk($fotosAntesFiltradas, 2);
        @endphp
        @foreach($chunks as $chunk)
        <tr>
            @foreach($chunk as $item)
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 5px;">
                {{-- Las imágenes ya fueron procesadas por ImageHelper::preprocessImagesForPdf() --}}
                <img src="{{ $item['foto'] }}" alt="{{ $item['alt'] }}" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
            </td>
            @endforeach
            @if(count($chunk) == 1)
            <td style="width: 50%;"></td>
            @endif
        </tr>
        @endforeach
    </table>
    @endif

    <!-- FOTOS DURANTE -->
    @if($registro->foto_uno_durante || $registro->foto_dos_durante || $registro->foto_tres_durante ||
    $registro->foto_cuatro_durante || $registro->foto_cinco_durante || $registro->foto_seis_durante)
    <table style="margin-top: 5px;">
        <tr>
            <th colspan="2" style="background-color: #FF7C61; text-align: center;">FOTOS DURANTE EL SERVICIO</th>
        </tr>
    </table>
    <table style="margin-top: 3px;">
        @php
        $fotosDurante = [
            ['foto' => $registro->foto_uno_durante, 'alt' => 'Foto 1 Durante'],
            ['foto' => $registro->foto_dos_durante, 'alt' => 'Foto 2 Durante'],
            ['foto' => $registro->foto_tres_durante, 'alt' => 'Foto 3 Durante'],
            ['foto' => $registro->foto_cuatro_durante, 'alt' => 'Foto 4 Durante'],
            ['foto' => $registro->foto_cinco_durante, 'alt' => 'Foto 5 Durante'],
            ['foto' => $registro->foto_seis_durante, 'alt' => 'Foto 6 Durante'],
        ];
        $fotosDuranteFiltradas = array_filter($fotosDurante, function($item) {
            return !empty($item['foto']);
        });
        $chunks = array_chunk($fotosDuranteFiltradas, 2);
        @endphp
        @foreach($chunks as $chunk)
        <tr>
            @foreach($chunk as $item)
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 5px;">
                {{-- Las imágenes ya fueron procesadas por ImageHelper::preprocessImagesForPdf() --}}
                <img src="{{ $item['foto'] }}" alt="{{ $item['alt'] }}" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
            </td>
            @endforeach
            @if(count($chunk) == 1)
            <td style="width: 50%;"></td>
            @endif
        </tr>
        @endforeach
    </table>
    @endif

    <!-- FOTOS DESPUÉS -->
    @if($registro->foto_uno_despues || $registro->foto_dos_despues || $registro->foto_tres_despues)
    <table style="margin-top: 5px;">
        <tr>
            <th colspan="2" style="background-color: #FF7C61; text-align: center;">FOTOS DESPUÉS DEL SERVICIO</th>
        </tr>
    </table>
    <table style="margin-top: 3px;">
        @php
        $fotosDespues = [
            ['foto' => $registro->foto_uno_despues, 'alt' => 'Foto 1 Después'],
            ['foto' => $registro->foto_dos_despues, 'alt' => 'Foto 2 Después'],
            ['foto' => $registro->foto_tres_despues, 'alt' => 'Foto 3 Después'],
        ];
        $fotosDespuesFiltradas = array_filter($fotosDespues, function($item) {
            return !empty($item['foto']);
        });
        $chunks = array_chunk($fotosDespuesFiltradas, 2);
        @endphp
        @foreach($chunks as $chunk)
        <tr>
            @foreach($chunk as $item)
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 5px;">
                {{-- Las imágenes ya fueron procesadas por ImageHelper::preprocessImagesForPdf() --}}
                <img src="{{ $item['foto'] }}" alt="{{ $item['alt'] }}" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
            </td>
            @endforeach
            @if(count($chunk) == 1)
            <td style="width: 50%;"></td>
            @endif
        </tr>
        @endforeach
    </table>
    @endif

    <!-- FOOTER PÁGINA 2 -->
    <div class="footer-text" style="margin-top: 10px;">
        <strong>Carrera 83 No. 72B 06 Tel: (571) 694 9133 / 694 9128 / 694 9125 www.genservices.com.co Bogotá, D.C. - Colombia</strong><br>
        <p>Las partes interesadas que intervienen en este informe, suscriben el presente
            COMPROMISO DE CONFIDENCIALIDAD Y NO DIVULGACIÓN DE LA INFORMACIÓN,
            que está clasificada como reservada, en la entrega del informe, esta únicamente será
            compartida con las sucursales, empresas y personas autorizadas para el desarrollo del
            servicio. Las partes interesadas cumplirán los requisitos legales y reglamentarios
            relacionados con las política de confidencialidad de la Empresa GEN SERVICES S.A.S.,
            por lo tanto, no se emitirán copias, divulgaciones o reproducciones a tercero,
            contenidas en el sistema.</p>
    </div>
</body>

</html>
