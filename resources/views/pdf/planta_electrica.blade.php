<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Servicio Técnico</title>
    <style>
        @page { margin: 5mm; }
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
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
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
        .two-col-layout {
            width: 100%;
        }
        .two-col-layout td {
            vertical-align: top;
            padding: 0;
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
                <div style="font-size: 11px; font-weight: bold;">HOJA DE SERVICIO TÉCNICO PARA PLANTAS ELÉCTRICAS</div>
                <div style="font-size: 9px; margin-top: 2px;">
                    <strong>CÓDIGO FR - HST V3</strong><br>
                    FECHA: {{ $registro->created_at->format('d/m/Y') }}
                </div>
            </td>
            <td class="code-cell">
                <div style="font-size: 11px; font-weight: bold;">
                    N°<br>{{ str_pad($registro->numero_orden, 4, '0', STR_PAD_LEFT) }}
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
            <td>{{ $solicitud->sucursal->address }}</td>
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
            <th style="width: 16%;">POTENCIA</th>
            <td style="width: 17%;">{{ $solicitud->equipo->potencia }}</td>
            <th style="width: 16%;">MODELO DEL EQUIPO</th>
            <td style="width: 17%;">{{ $solicitud->equipo->modelo_equipo }}</td>
            <th style="width: 16%;">MODELO DEL MOTOR</th>
            <td style="width: 18%;">{{ $solicitud->equipo->modelo_motor }}</td>
        </tr>
        <tr>
            <th>TENSIÓN DE OPERACIÓN</th>
            <td>{{ $registro->tension_operacion }}</td>
            <th>SERIE DEL EQUIPO</th>
            <td>{{ $registro->serie_equipo }}</td>
            <th>SERIE DEL MOTOR</th>
            <td>{{ $registro->serie_motor }}</td>
        </tr>
        <tr>
            <th>MARCA DEL GENERADOR</th>
            <td>{{ $registro->marca_generador }}</td>
            <th>HORÓMETRO</th>
            <td>{{ $registro->horometro }}</td>
            <th>MARCA DEL MOTOR</th>
            <td>{{ $registro->marca_motor }}</td>
        </tr>
        <tr>
            <th>TIPO DE SERVICIO</th>
            <td colspan="5">
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Mantenimiento' ? 'X' : '' }}</span> MANTENIMIENTO
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Servicio' ? 'X' : '' }}</span> SERVICIO
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Inspeccion' ? 'X' : '' }}</span> INSPECCIÓN
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Soporte' ? 'X' : '' }}</span> SOPORTE
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Emergencia' ? 'X' : '' }}</span> EMERGENCIA
                <span class="checkbox-small">{{ $registro->tipo_servicio == 'Otro' ? 'X' : '' }}</span> OTRO
            </td>
        </tr>
    </table>

    <!-- OBSERVACIONES INICIALES -->
    <table>
        <tr>
            <th>OBSERVACIONES INICIALES</th>
        </tr>
        <tr>
            <td style="min-height: 30px; vertical-align: top;">{!! $registro->observaciones_iniciales !!}</td>
        </tr>
    </table>

    <!-- ESTADO INICIAL Y ACTIVIDAD REALIZADA (DOS COLUMNAS) -->
    <table class="two-col-layout">
        <tr>
            <td style="width: 38%; padding: 0;">
                <!-- ESTADO INICIAL -->
                <table style="margin: 0; height: 10%;">
                    <tr>
                        <th colspan="4" style="background-color: #FF7C61;">ESTADO INICIAL</th>
                    </tr>
                    <tr style="background-color: #d8d8d8;">
                        <th style="width: 52%;"></th>
                        <th style="width: 16%; text-align: center;">B</th>
                        <th style="width: 16%; text-align: center;">R</th>
                        <th style="width: 16%; text-align: center;">M</th>
                    </tr>
                    @foreach ([
                        'nivel_aceite' => 'NIVEL DE ACEITE',
                        'nivel_refrigerante' => 'NIVEL DE REFRIGERANTE',
                        'nivel_combustible' => 'NIVEL DE COMBUSTIBLE',
                        'capacidad_tanque' => 'CAPACIDAD DEL TANQUE',
                        'fugas' => 'FUGAS',
                        'mangueras' => 'MANGUERAS',
                        'sellos' => 'SELLOS',
                        'tuberias' => 'TUBERÍAS',
                        'radiador' => 'RADIADOR',
                        'guardas' => 'GUARDAS',
                        'correas_ventilador' => 'CORREAS VENTILADOR',
                        'correas_alternador' => 'CORREAS ALTERNADOR',
                        'amortiguadores' => 'AMORTIGUADORES',
                        'precalentador_estado_inicial' => 'PRECALENTADOR',
                        'bateria' => 'BATERÍA',
                        'nivel_electrolito' => 'NIVEL ELECTROLITO',
                        'voltaje_bateria' => 'VOLTAJE DE BATERÍA',
                        'estado_cargador' => 'CARGADOR',
                        'voltaje_cargador' => 'VOLTAJE CARGADOR',
                        'voltaje_alternador' => 'VOLTAJE ALTERNADOR',
                        'tipo_control' => 'TIPO CONTROL',
                        'conexiones_control' => 'CONEXIONES DE CONTROL',
                        'conexiones_potencia' => 'CONEXIONES DE POTENCIA',
                        'estado_generador' => 'ESTADO DE GENERADOR',
                        'limpieza_generador' => 'LIMPIEZA GENERAL',
                    ] as $campo => $label)
                    <tr>
                        <td style="font-size: 6px; padding: 0.5px 2px;">{{ $label }}</td>
                        <td style="text-align: center; padding: 0.5px;">
                            <span class="checkbox-small">{{ $registro->$campo == 'B' ? 'X' : '' }}</span>
                        </td>
                        <td style="text-align: center; padding: 0.5px;">
                            <span class="checkbox-small">{{ $registro->$campo == 'R' ? 'X' : '' }}</span>
                        </td>
                        <td style="text-align: center; padding: 0.5px;">
                            <span class="checkbox-small">{{ $registro->$campo == 'M' ? 'X' : '' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
            <td style="width: 62%; padding: 0;">
                <!-- ACTIVIDAD REALIZADA -->
                <table style="margin: 0;">
                    <tr>
                        <th style="background-color: #FF7C61;">ACTIVIDAD REALIZADA</th>
                    </tr>
                    <tr>
                        <td style="min-height: 80px; vertical-align: top; font-size: 10px; padding: 5px;">{!! $registro->actividad_realizada !!}</td>
                    </tr>
                </table>
                
                <!-- PRUEBAS CON EQUIPO OPERANDO -->
                <table style="margin: 0; margin-top: 2px;">
                    <tr>
                        <th colspan="6" style="background-color: #b8b8b8;">PRUEBAS CON EQUIPO OPERANDO</th>
                    </tr>
                    <tr style="background-color: #d8d8d8;">
                        <th colspan="3" style="text-align: center; font-size: 9px;">MOTOR</th>
                        <th colspan="3" style="text-align: center; font-size: 9px;">GENERADOR</th>
                    </tr>
                    <tr style="background-color: #d8d8d8;">
                        <th style="width: 20%; font-size: 8px;"></th>
                        <th style="width: 10%; font-size: 8px; text-align: center;">Valor</th>
                        <th style="width: 10%; font-size: 8px; text-align: center;">Unidad</th>
                        <th style="width: 20%; font-size: 8px;"></th>
                        <th style="width: 20%; font-size: 8px; text-align: center;">Valor</th>
                        <th style="width: 20%; font-size: 8px; text-align: center;">Unidad</th>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;">RPM</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->valor_rpm }}</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->cantidad_rpm }}</td>
                        <td style="font-size: 8px;">VAC FASES</td>
                        <td style="font-size: 8px; text-align: center;">L1-L2</td>
                        <td style="font-size: 8px; text-align: center;">AMPERIOS</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;">PRESIÓN ACEITE</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->valor_presion_aceite }}</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->cantidad_presion_aceite }}</td>
                        <td style="font-size: 8px;">{{ $registro->vac_fases_l1_l2 }}</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->amperios_l1 }}</td>
                        <td style="font-size: 8px; text-align: center;">L1</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;">TEMP. REFRIG.</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->valor_temp_refrigerante }}</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->cantidad_temp_refrigerante }}</td>
                        <td style="font-size: 8px;">{{ $registro->vac_fases_l2_l3 }}</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->amperios_l2 }}</td>
                        <td style="font-size: 8px; text-align: center;">L2</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;">TEMP. ACEITE</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->valor_temp_aceite }}</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->cantidad_temp_aceite }}</td>
                        <td style="font-size: 8px;">{{ $registro->vac_fases_l1_l3 }}</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->amperios_l3 }}</td>
                        <td style="font-size: 8px; text-align: center;">L3</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;">RPM</td>
                        <td style="font-size: 8px; text-align: center;"></td>
                        <td style="font-size: 8px; text-align: center;"></td>
                        <td style="font-size: 8px;">POTENCIA</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->potencia }}</td>
                        <td style="font-size: 8px; text-align: center;">KW</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;"></td>
                        <td style="font-size: 8px; text-align: center;"></td>
                        <td style="font-size: 8px; text-align: center;"></td>
                        <td style="font-size: 8px;">HZ</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->hz }}</td>
                        <td style="font-size: 8px; text-align: center;">HZ</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;"></td>
                        <td style="font-size: 8px; text-align: center;"></td>
                        <td style="font-size: 8px; text-align: center;"></td>
                        <td style="font-size: 8px;">FP</td>
                        <td style="font-size: 8px; text-align: center;">{{ $registro->fp }}</td>
                        <td style="font-size: 8px; text-align: center;">FP</td>
                    </tr>
                </table>

                <!-- PROTECCIONES -->
                <table style="margin: 0; margin-top: 2px;">
                    <tr>
                        <th colspan="6" style="background-color: #b8b8b8;">PROTECCIONES</th>
                    </tr>
                    <tr>
                        <td style="font-size: 8px; width: 25%;">BAJA PRESIÓN</td>
                        <td style="font-size: 8px; width: 8%;">{{ $registro->baja_presion }}</td>
                        <td style="font-size: 8px; width: 25%;">ALTA TEMPERATURA</td>
                        <td style="font-size: 8px; width: 9%;">{{ $registro->alta_temperatura }}</td>
                        <td style="font-size: 8px; width: 25%;">BAJO NIVEL REFRIG.</td>
                        <td style="font-size: 8px; width: 8%;">{{ $registro->bajo_nivel_refrigerante }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 8px;">BAJO VOLTAJE AC</td>
                        <td style="font-size: 8px;">{{ $registro->bajo_voltaje_ac }}</td>
                        <td colspan="4"></td>
                    </tr>
                </table>

                <!-- RECOMENDACIONES -->
                <table style="margin: 0; margin-top: 2px;">
                    <tr>
                        <th style="background-color: #b8b8b8;">RECOMENDACIONES</th>
                    </tr>
                    <tr>
                        <td style="min-height: 25px; vertical-align: top; font-size: 10px; padding: 5px 5px;">{!! $registro->recomendaciones !!}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- FILTROS -->
    <table>
        <tr>
            <th style="width: 40%; background-color: #b8b8b8;">FILTRO DE AIRE</th>
            <td style="width: 10%;">{{ $registro->cantidad_filtro_aire }}</td>
            <td style="width: 40%;">{{ $registro->referencia_filtro_aire }}</td>
            <th style="width: 40%; background-color: #b8b8b8;">LLEGADA TECNICO</th>
            <td rowspan="6" style="width: 10%;">
                Día: {{ date('d', strtotime($registro->llegada_tecnico)) }}<br>
                Mes: {{ date('m', strtotime($registro->llegada_tecnico)) }}<br>
                Año: {{ date('Y', strtotime($registro->llegada_tecnico)) }}<br>
                Hora: {{ date('H:i', strtotime($registro->llegada_tecnico)) }}
            </td>
        </tr>
        <tr>
            <th style="background-color: #b8b8b8;">FILTRO DE ACEITE</th>
            <td>{{ $registro->cantidad_filtro_aceite }}</td>
            <td>{{ $registro->referencia_filtro_aceite }}</td>
            <th style="background-color: #b8b8b8;">SALIDA TECNICO</th>
        </tr>
        <tr>
            <th style="background-color: #b8b8b8;">FILTRO DE COMBUSTIBLE</th>
            <td>{{ $registro->cantidad_filtro_combustible }}</td>
            <td>{{ $registro->referencia_filtro_combustible }}</td>
            <td rowspan="4">
                Día: {{ date('d', strtotime($registro->salida_tecnico)) }}<br>
                Mes: {{ date('m', strtotime($registro->salida_tecnico)) }}<br>
                Año: {{ date('Y', strtotime($registro->salida_tecnico)) }}<br>
                Hora: {{ date('H:i', strtotime($registro->salida_tecnico)) }}
            </td>
        </tr>
        <tr>
            <th style="background-color: #b8b8b8;">FILTRO SEPARADOR</th>
            <td>{{ $registro->cantidad_filtro_separador }}</td>
            <td>{{ $registro->referencia_filtro_separador }}</td>
        </tr>
        <tr>
            <th style="background-color: #b8b8b8;">FILTRO DE AGUA</th>
            <td>{{ $registro->cantidad_filtro_agua }}</td>
            <td>{{ $registro->referencia_filtro_agua }}</td>
        </tr>
        <tr>
            <th style="background-color: #b8b8b8;">CANTIDAD DE ACEITE</th>
            <td>{{ $registro->cantidad_cantidad_aceite }}</td>
            <td>{{ $registro->referencia_cantidad_aceite }}</td>
        </tr>
    </table>

    <!-- POSICIÓN DE INSTRUMENTOS -->
    <table>
        <tr>
            <th colspan="4" style="background-color: #b8b8b8;">POSICIÓN DE INSTRUMENTOS AL CONCLUIR EL SERVICIO</th>
            <th colspan="3" style="background-color: #b8b8b8;">CALIFICACIÓN DEL SERVICIO (por parte del cliente)</th>
        </tr>
        <tr style="background-color: #d8d8d8;">
            <th style="width: 30%;"></th>
            <th style="width: 8%; text-align: center;">M</th>
            <th style="width: 8%; text-align: center;">A</th>
            <th style="width: 8%; text-align: center;">OFF</th>
            <td rowspan="6" colspan="3" style="text-align: center;">
                <span class="checkbox-small">{{ $registro->calificacion_servicio == 'Bueno' ? 'X' : '' }}</span> BUENO &nbsp;
                <span class="checkbox-small">{{ $registro->calificacion_servicio == 'Regular' ? 'X' : '' }}</span> REGULAR &nbsp;
                <span class="checkbox-small">{{ $registro->calificacion_servicio == 'Malo' ? 'X' : '' }}</span> MALO
            </td>
        </tr>
        <tr>
            <td>CONTROL</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->control == 'M' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->control == 'A' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->control == 'OFF' ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>TRANSFERENCIA</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->transferencia == 'M' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->transferencia == 'A' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->transferencia == 'OFF' ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>CARGADOR</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->posicion_cargador == 'ON' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->posicion_cargador == 'OFF' ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>TOTALIZADOR</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->totalizador == 'ON' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->totalizador == 'OFF' ? 'X' : '' }}</span></td>
        </tr>
        <tr>
            <td>PRECALENTADOR</td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->precalentador_posicion == 'ON' ? 'X' : '' }}</span></td>
            <td style="text-align: center;"></td>
            <td style="text-align: center;"><span class="checkbox-small">{{ $registro->precalentador_posicion == 'OFF' ? 'X' : '' }}</span></td>
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
                <img src="{{ $registro->firma_tecnico }}" alt="Firma Técnico" style="max-width: 100%; max-height: 35px;">
                @endif
            </td>
            <td class="firma-box">
                @if($registro->firma_cliente)
                <img src="{{ $registro->firma_cliente }}" alt="Firma Cliente" style="max-width: 100%; max-height: 35px;">
                @endif
            </td>
        </tr>
        <tr>
            <th>FIRMA:</th>
            <th>FIRMA:</th>
        </tr>
        <tr>
            <td style="font-size: 6px;">
                Nombre: {{ $registro->nombre_tecnico }}<br>
                Cédula: {{ $registro->cedula_tecnico }}
            </td>
            <td style="font-size: 6px;">
                Nombre: {{ $registro->nombre_cliente }}<br>
                Cédula: {{ $registro->cedula_cliente }}
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer-text">
        <strong>Carrera 83 No. 72B 06 Tel: (571) 694 9133 / 694 9128 / 694 9125 www.genservices.com.co Bogotá, D.C. - Colombia</strong><br>
        Las partes interesadas que intervienen en este informe, suscriben el presente COMPROMISO DE CONFIDENCIALIDAD Y NO DIVULGACIÓN DE LA INFORMACIÓN.
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
                <div style="font-size: 11px; font-weight: bold;">REGISTRO FOTOGRÁFICO</div>
                <div style="font-size: 9px; margin-top: 2px;">
                    <strong>CÓDIGO FR - HST V3</strong><br>
                    FECHA: {{ $registro->created_at->format('d/m/Y') }}
                </div>
            </td>
            <td class="code-cell">
                <div style="font-size: 11px; font-weight: bold;">
                    N°<br>{{ str_pad($registro->numero_orden, 4, '0', STR_PAD_LEFT) }}
                </div>
            </td>
        </tr>
    </table>

    <!-- FOTOS ANTES -->
    @if($registro->foto_uno_antes || $registro->foto_dos_antes || $registro->foto_tres_antes)
    <table>
        <tr>
            <th colspan="2" style="background-color: #FF7C61; text-align: center;">FOTOS ANTES DEL SERVICIO</th>
        </tr>
    </table>
    <table style="margin-top: 3px;">
        <tr>
            @if($registro->foto_uno_antes)
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 5px;">
                <img src="uploads/{{ $registro->foto_uno_antes }}" alt="uploads/{{ $registro->foto_uno_antes }}" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
                @if($registro->pie_foto_uno_antes)
                <div style="font-size: 8px; margin-top: 3px; text-align: center;">{{ $registro->pie_foto_uno_antes }}</div>
                @endif
            </td>
            @endif
            @if($registro->foto_dos_antes)
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 5px;">
                <img src="uploads/{{ $registro->foto_dos_antes }}" alt="uploads/{{ $registro->foto_dos_antes }}" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
                @if($registro->pie_foto_dos_antes)
                <div style="font-size: 8px; margin-top: 3px; text-align: center;">{{ $registro->pie_foto_dos_antes }}</div>
                @endif
            </td>
            @endif
        </tr>
        @if($registro->foto_tres_antes)
        <tr>
            <td colspan="2" style="text-align: center; vertical-align: top; padding: 5px;">
                <img src="uploads/{{ $registro->foto_tres_antes }}" alt="uploads/{{ $registro->foto_tres_antes }}" style="max-width: 47%; max-height: 180px; border: 1px solid #ccc;">
                @if($registro->pie_foto_tres_antes)
                <div style="font-size: 8px; margin-top: 3px; text-align: center;">{{ $registro->pie_foto_tres_antes }}</div>
                @endif
            </td>
        </tr>
        @endif
    </table>
    @endif

    <!-- FOTOS DURANTE -->
    @if($registro->foto_uno_durante || $registro->foto_dos_durante || $registro->foto_tres_durante || 
        $registro->foto_cuatro_durante || $registro->foto_cinco_durante || $registro->foto_seis_durante ||
        $registro->foto_siete_durante || $registro->foto_ocho_durante || $registro->foto_nueve_durante)
    <table style="margin-top: 5px;">
        <tr>
            <th colspan="2" style="background-color: #FF7C61; text-align: center;">FOTOS DURANTE EL SERVICIO</th>
        </tr>
    </table>
    <table style="margin-top: 3px;">
        @php
            $fotosDurante = [
                ['foto' => $registro->foto_uno_durante, 'pie' => $registro->pie_foto_uno_durante],
                ['foto' => $registro->foto_dos_durante, 'pie' => $registro->pie_foto_dos_durante],
                ['foto' => $registro->foto_tres_durante, 'pie' => $registro->pie_foto_tres_durante],
                ['foto' => $registro->foto_cuatro_durante, 'pie' => $registro->pie_foto_cuatro_durante],
                ['foto' => $registro->foto_cinco_durante, 'pie' => $registro->pie_foto_cinco_durante],
                ['foto' => $registro->foto_seis_durante, 'pie' => $registro->pie_foto_seis_durante],
                ['foto' => $registro->foto_siete_durante, 'pie' => $registro->pie_foto_siete_durante],
                ['foto' => $registro->foto_ocho_durante, 'pie' => $registro->pie_foto_ocho_durante],
                ['foto' => $registro->foto_nueve_durante, 'pie' => $registro->pie_foto_nueve_durante],
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
                <img src="uploads/{{ $item['foto'] }}" alt="Foto Durante" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
                @if($item['pie'])
                <div style="font-size: 8px; margin-top: 3px; text-align: center;">{{ $item['pie'] }}</div>
                @endif
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
        <tr>
            @if($registro->foto_uno_despues)
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 5px;">
                <img src="{{ $registro->foto_uno_despues }}" alt="Foto 1 Después" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
                @if($registro->pie_foto_uno_despues)
                <div style="font-size: 8px; margin-top: 3px; text-align: center;">{{ $registro->pie_foto_uno_despues }}</div>
                @endif
            </td>
            @endif
            @if($registro->foto_dos_despues)
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 5px;">
                <img src="{{ $registro->foto_dos_despues }}" alt="Foto 2 Después" style="max-width: 95%; max-height: 180px; border: 1px solid #ccc;">
                @if($registro->pie_foto_dos_despues)
                <div style="font-size: 8px; margin-top: 3px; text-align: center;">{{ $registro->pie_foto_dos_despues }}</div>
                @endif
            </td>
            @endif
        </tr>
        @if($registro->foto_tres_despues)
        <tr>
            <td colspan="2" style="text-align: center; vertical-align: top; padding: 5px;">
                <img src="{{ $registro->foto_tres_despues }}" alt="Foto 3 Después" style="max-width: 47%; max-height: 180px; border: 1px solid #ccc;">
                @if($registro->pie_foto_tres_despues)
                <div style="font-size: 8px; margin-top: 3px; text-align: center;">{{ $registro->pie_foto_tres_despues }}</div>
                @endif
            </td>
        </tr>
        @endif
    </table>
    @endif

    <!-- FOOTER PÁGINA 2 -->
    <div class="footer-text" style="margin-top: 10px;">
        <strong>Carrera 83 No. 72B 06 Tel: (571) 694 9133 / 694 9128 / 694 9125 www.genservices.com.co Bogotá, D.C. - Colombia</strong><br>
        Las partes interesadas que intervienen en este informe, suscriben el presente COMPROMISO DE CONFIDENCIALIDAD Y NO DIVULGACIÓN DE LA INFORMACIÓN.
    </div>
</body>
</html>
