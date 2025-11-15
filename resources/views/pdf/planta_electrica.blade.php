<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Hoja de Servicio Técnico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0px;
        }
        .header, .footer {
            text-align: center;
        }
        .header img {
            max-width: 100px;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .section {
            margin-top: 5px;
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
        }
        .section table, .section th, .section td {
            border: 1px solid black;
        }
        .section th, .section td {
            padding: 2px;
            text-align: left;
        }
        .section th {
            background-color: #db001228;
            padding: 5px;
        }
        .small-text {
            font-size: 10px;
        }
        .bold {
            font-weight: bold;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            line-height: 12px;
        }
        .option-group {
            display: inline-block;
            margin-right: 10px;
        }
        .option-group span {
            color: #ccc;
        }
        .option-group .selected {
            color: #000;
            font-weight: bold;
        }
        .firma-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .firma {
            width: 45%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        
    </style>
</head>
<body>
    <header>
        <div style="float: left; width: 75%; text-align: left;">
            <strong>GEN SERVICES SAS</strong><br>
            <b>NIT: 900748881-8</b><br>
            Fecha del Informe: {{ $registro->created_at->format('d/m/Y') }}<br>
            <b>Dirección de la Empresa:</b><br>
            Colombia, Bogotá, DIR. CRA 83 #72B-06<br>
            
            
            <b>Datos de contacto:</b><br>
            genservices@outlook.com<br>
            TEL. 6949133 -  CEL. 3005231475
        </div>
        <div style="float: right; width: 25%;">
            <img src="{{ public_path('logo_empresa.png') }}" alt="Logo Empresa" style="width: 200px; height: auto;">
            <p style="font-size: 8px; text-align: right !important;"><b>CONFIABILIDAD, CALIDAD Y CUMPLIMIENTO</b></p>
        </div>
        
        <div style="clear: both;"></div>
    </header>
    <div class="container">
        <div class="header">
            <h1 class="title">HOJA DE SERVICIO TÉCNICO PARA PLANTAS ELÉCTRICAS</h1>
            <p class="small-text">Versión: 05 | Fecha: 1 de julio de 2024 {{ $registro->fecha_solicitud }} | Consecutivo: {{ $registro->numero_orden }}</p>
        </div>
        
        <div class="section">
            <table>
                <tr>
                    <th>Día</th>
                    <th>Mes</th>
                    <th>Año</th>
                    <th>Ubicacion</th>
                </tr>
                <tr>
                    <td>{{ date('d', strtotime($registro->created_at)) }}</td>
                    <td>{{ date('m', strtotime($registro->created_at)) }}</td>
                    <td>{{ date('Y', strtotime($registro->created_at)) }}</td>
                    <td>{{ $solicitud->sucursal->address }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table>
                <tr>
                    <th colspan="4">DATOS DEL CLIENTE</th>
                </tr>
                <tr>
                    <th>Razón Social</th>
                    <td colspan="3">{{ $solicitud->client->enterprise_name }}</td>
                </tr>
                <tr>
                    <th>Sucursal</th>
                    <td>{{ $solicitud->sucursal->name }}</td>
                    <th>Contacto</th>
                    <td>{{ $solicitud->sucursal->contact_name }}</td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td>{{ $registro->sucursal->address }}</td>
                    <th>E-Mail</th>
                    <td>{{ $registro->sucursal->email }}</td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td>{{ $solicitud->sucursal->phone_number }}</td>
                    <th></th>
                    <td></td>
                </tr>
            </table>
        </div>

       <div class="section">
    <table>
        <tr>
            <th colspan="6">DATOS DEL EQUIPO</th>
        </tr>
        <tr>
            <th>Potencia</th>
            <td>{{ $solicitud->equipo->potencia }}</td>
            <th>Modelo del Equipo</th>
            <td>{{ $solicitud->equipo->modelo_equipo }}</td>
            <th>Modelo del Motor</th>
            <td>{{ $solicitud->equipo->modelo_motor }}</td>
        </tr>
        <tr>
            <th>Tensión de Operación</th>
            <td>{{ $registro->tension_operacion }}</td>
            <th>Serie del Equipo</th>
            <td>{{ $registro->serie_equipo }}</td>
            <th>Serie del Motor</th>
            <td>{{ $registro->serie_motor }}</td>
        </tr>
        <tr>
            <th>Marca del Generador</th>
            <td>{{ $registro->marca_generador }}</td>
            <th>Horómetro</th>
            <td>{{ $registro->horometro }}</td>
            <th>Marca del Motor</th>
            <td>{{ $registro->marca_motor }}</td>
        </tr>
        <tr>
            <th>Tipo de Servicio</th>
            <td colspan="5">
                <label class="checkbox">{{ $registro->tipo_servicio == 'Mantenimiento' ? 'X' : '' }}</label> Mantenimiento
                <label class="checkbox">{{ $registro->tipo_servicio == 'Servicio' ? 'X' : '' }}</label> Servicio
                <label class="checkbox">{{ $registro->tipo_servicio == 'Inspeccion' ? 'X' : '' }}</label> Inspección
                <label class="checkbox">{{ $registro->tipo_servicio == 'Soporte' ? 'X' : '' }}</label> Soporte
                <label class="checkbox">{{ $registro->tipo_servicio == 'Emergencia' ? 'X' : '' }}</label> Emergencia
                <label class="checkbox">{{ $registro->tipo_servicio == 'Otro' ? 'X' : '' }}</label> Otro
            </td>
        </tr>
    </table>
</div>

        <div class="section">
            <table>
                <tr>
                    <th colspan="3">OBSERVACIONES INICIALES</th>
                </tr>
                <tr>
                    <td style="text-align: center" colspan="3">{!! $registro->observaciones_iniciales !!}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 10px;"  class="section-title">FOTOS ESTADO INICIAL</div>
        @if ($registro->foto_uno_antes || $registro->foto_dos_antes || $registro->foto_tres_antes)
         <div class="images" style="margin-top: 10px;">
            @if ($registro->foto_uno_antes)
            <div style="display: inline-block; text-align: center; margin-right: 5px;">
                <img style="width: 232px; height: 175px" src="{{ public_path('uploads/' . $registro->foto_uno_antes) }}" alt="Foto 1">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_uno_antes }}</p>
            </div>
            @endif
            @if ($registro->foto_dos_antes)
            <div style="display: inline-block; text-align: center; margin-right: 5px;">
                <img style="width: 232px; height: 175px" src="{{ public_path('uploads/' . $registro->foto_dos_antes) }}">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_dos_antes }}</p>
            </div>
            @endif
            @if ($registro->foto_tres_antes)
            <div style="display: inline-block; text-align: center;">
                <img style="width: 232px; height: 175px" src="{{ public_path('uploads/' . $registro->foto_tres_antes) }}">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_tres_antes }}</p>
            </div>
            @endif
        </div>
        @else
        <p>No hay fotos disponibles.</p>
        @endif
       

        <div style="margin-top: 80px !important" class="section">
            <table>
                <tr>
                    <th>Estado Inicial</th>
                    <th>Detalles</th>
                </tr>
                <tr>
                    <td style="width: 40% !important;">
                        <table>
                            @foreach ([
                                'nivel_aceite' => 'Nivel de Aceite',
                                'nivel_refrigerante' => 'Nivel de Refrigerante',
                                'nivel_combustible' => 'Nivel de Combustible',
                                'capacidad_tanque' => 'Capacidad del Tanque',
                                'fugas' => 'Fugas',
                                'mangueras' => 'Mangueras',
                                'sellos' => 'Sellos',
                                'tuberias' => 'Tuberas',
                                'radiador' => 'Radiador',
                                'guardas' => 'Guardas',
                                'correas_ventilador' => 'Correas Ventilador',
                                'correas_alternador' => 'Correas Alternador',
                                'amortiguadores' => 'Amortiguadores',
                                'precalentador_estado_inicial' => 'Precalentador',
                                'bateria' => 'Batería',
                                'nivel_electrolito' => 'Nivel Electrolito',
                                'voltaje_bateria' => 'Voltaje de Batería',
                                'estado_cargador' => 'Cargador',
                                'voltaje_cargador' => 'Voltaje Cargador',
                                'voltaje_alternador' => 'Voltaje Alternador',
                                'tipo_control' => 'Tipo Control',
                                'conexiones_control' => 'Conexiones de Control',
                                'conexiones_potencia' => 'Conexiones de Potencia',
                                'estado_generador' => 'Estado de Generador',
                                'limpieza_generador' => 'Limpieza General',
                            ] as $campo => $label)
                            <tr>
                                <th style="font-size: 10px !important; padding: 1.5px 0.5px !important;">{{ $label }}</th>
                                <td style="font-size: 10px !important; padding: 1.5px 0.5px !important;">{{ $registro->$campo }}</td>
                                <td style="font-size: 10px !important; padding: 1.5px 0.5px !important;" class="option-group">
                                    <span style="font-size: 10px !important; padding: 1.5px 0.5px !important;" class="{{ $registro->$campo == 'B' ? 'selected' : '' }}">B</span>
                                    <span style="font-size: 10px !important; padding: 1.5px 0.5px !important;" class="{{ $registro->$campo == 'R' ? 'selected' : '' }}">R</span>
                                    <span style="font-size: 10px !important; padding: 1.5px 0.5px !important;" class="{{ $registro->$campo == 'M' ? 'selected' : '' }}">M</span>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td style="width: 60% !important;">
                        <table>
                            <tr>
                                <th colspan="6">ACTIVIDAD REALIZADA</th>
                            </tr>
                            <tr>
                                <td colspan="6">{!! $registro->actividad_realizada !!}</td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <th>Motor</th>
                                <th>Valor</th>
                                <th>Unidad</th>
                                <th>Generador</th>
                                <th>Valor</th>
                                <th>Unidad</th>
                            </tr>
                            <tr>
                                <td>RPM</td>
                                <td>{{ $registro->valor_rpm }}</td>
                                <td>{{ $registro->cantidad_rpm }}</td>
                                <td>{{ $registro->vac_fases_l1_l2 }}</td>
                                <td>{{ $registro->amperios_l1 }}</td>
                                <td>{{ $registro->potencia }}</td>
                            </tr>
                            <tr>
                                <td>Presión de Aceite</td>
                                <td>{{ $registro->valor_presion_aceite }}</td>
                                <td>{{ $registro->cantidad_presion_aceite }}</td>
                                <td>{{ $registro->vac_fases_l2_l3 }}</td>
                                <td>{{ $registro->amperios_l2 }}</td>
                                <td>{{ $registro->hz }}</td>
                            </tr>
                            <tr>
                                <td>Temp. de Refrigerante</td>
                                <td>{{ $registro->valor_temp_refrigerante }}</td>
                                <td>{{ $registro->cantidad_temp_refrigerante }}</td>
                                <td>{{ $registro->vac_fases_l1_l3 }}</td>
                                <td>{{ $registro->amperios_l3 }}</td>
                                <td>{{ $registro->fp }}</td>
                            </tr>
                            <tr>
                                <td>Temp. de Turbo</td>
                                <td>{{ $registro->valor_temp_turbo }}</td>
                                <td>{{ $registro->cantidad_temp_turbo }}</td>
                                <td>{{ $registro->vac_fase_n_l1n }}</td>
                                <td>{{ $registro->amperios_l1 }}</td>
                                <td>{{ $registro->potencia }}</td>
                            </tr>
                            <tr>
                                <td>Voltaje de Batería</td>
                                <td>{{ $registro->valor_voltaje_bateria }}</td>
                                <td>{{ $registro->cantidad_voltaje_bateria }}</td>
                                <td>{{ $registro->vac_fase_n_l2n }}</td>
                                <td>{{ $registro->amperios_l2 }}</td>
                                <td>{{ $registro->hz }}</td>
                            </tr>
                            <tr>
                                <td>Cada Voltaje de Bat.</td>
                                <td>{{ $registro->valor_caida_voltaje_bat }}</td>
                                <td>{{ $registro->cantidad_caida_voltaje_bat }}</td>
                                <td>{{ $registro->vac_fase_n_l3n }}</td>
                                <td>{{ $registro->amperios_l3 }}</td>
                                <td>{{ $registro->fp }}</td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <th colspan="6">Protecciones</th>
                            </tr>
                            <tr>
                                <td>Baja Presin</td>
                                <td>{{ $registro->baja_presion }}</td>
                                <td>Alta Temperatura</td>
                                <td>{{ $registro->alta_temperatura }}</td>
                                <td>Bajo Nivel Refrigerante</td>
                                <td>{{ $registro->bajo_nivel_refrigerante }}</td>
                            </tr>
                            <tr>
                                <td>Bajo Voltaje de AC</td>
                                <td>{{ $registro->bajo_voltaje_ac }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <th colspan="6">RECOMENDACIONES</th>
                            </tr>
                            <tr>
                                <td colspan="6">{!! $registro->recomendaciones !!}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        
         <div style="margin-top: 30px;" class="section-title">FOTOS DURANTE</div>
        @if ($registro->foto_uno_durante || $registro->foto_dos_durante || $registro->foto_tres_durante || 
             $registro->foto_cuatro_durante || $registro->foto_cinco_durante || $registro->foto_seis_durante ||
             $registro->foto_siete_durante || $registro->foto_ocho_durante || $registro->foto_nueve_durante)
        <div style="margin-top: 10px;" class="images">
            @if ($registro->foto_uno_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_uno_durante) }}" alt="Foto 1">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_uno_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_dos_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_dos_durante) }}" alt="Foto 2">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_dos_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_tres_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_tres_durante) }}" alt="Foto 3">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_tres_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_cuatro_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_cuatro_durante) }}" alt="Foto 4">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_cuatro_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_cinco_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_cinco_durante) }}" alt="Foto 5">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_cinco_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_seis_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_seis_durante) }}" alt="Foto 6">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_seis_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_siete_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_siete_durante) }}" alt="Foto 7">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_siete_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_ocho_durante)
            <div style="display: inline-block; text-align: center; margin-right: 5px; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_ocho_durante) }}" alt="Foto 8">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_ocho_durante }}</p>
            </div>
            @endif
            @if ($registro->foto_nueve_durante)
            <div style="display: inline-block; text-align: center; margin-bottom: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_nueve_durante) }}" alt="Foto 9">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_nueve_durante }}</p>
            </div>
            @endif
        </div>
        @else
        <p>No hay fotos disponibles.</p>
        @endif

        {{-- <div class="section">
            <table>
                <tr>
                    <th colspan="6">Protecciones</th>
                </tr>
                <tr>
                    <td>Baja Presión</td>
                    <td>{{ $registro->baja_presion }}</td>
                    <td>Alta Temperatura</td>
                    <td>{{ $registro->alta_temperatura }}</td>
                    <td>Bajo Nivel Refrigerante</td>
                    <td>{{ $registro->bajo_nivel_refrigerante }}</td>
                </tr>
                <tr>
                    <td>Bajo Voltaje de AC</td>
                    <td>{{ $registro->bajo_voltaje_ac }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table>
                <tr>
                    <th colspan="6">Recomendaciones</th>
                </tr>
                <tr>
                    <td colspan="6">{{ $registro->recomendaciones }}</td>
                </tr>
            </table>
        </div> --}}

        <div class="section">
            <table>
                <tr>
                    <th>Filtro de Aire</th>
                    <td>{{ $registro->cantidad_filtro_aire }}</td>
                    <td>{{ $registro->referencia_filtro_aire }}</td>
                </tr>
                <tr>
                    <th>Filtro de Aceite</th>
                    <td>{{ $registro->cantidad_filtro_aceite }}</td>
                    <td>{{ $registro->referencia_filtro_aceite }}</td>
                </tr>
                <tr>
                    <th>Filtro de Combustible</th>
                    <td>{{ $registro->cantidad_filtro_combustible }}</td>
                    <td>{{ $registro->referencia_filtro_combustible }}</td>
                </tr>
                <tr>
                    <th>Filtro Separador</th>
                    <td>{{ $registro->cantidad_filtro_separador }}</td>
                    <td>{{ $registro->referencia_filtro_separador }}</td>
                </tr>
                <tr>
                    <th>Filtro de Agua</th>
                    <td>{{ $registro->cantidad_filtro_agua }}</td>
                    <td>{{ $registro->referencia_filtro_agua }}</td>
                </tr>
                <tr>
                    <th>Cantidad de Aceite</th>
                    <td>{{ $registro->cantidad_cantidad_aceite }}</td>
                    <td>{{ $registro->referencia_cantidad_aceite }}</td>
                </tr>
            </table>
        </div>

        <div class="section" style="margin-top: 80px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <th colspan="4" style="text-align: left;">Posición de Instrumentos al Concluir el Servicio</th>
                </tr>
                <tr>
                    <th style="width: 60%;">&nbsp;</th>
                    <th style="width: 10%; text-align: center;">M</th>
                    <th style="width: 10%; text-align: center;">A</th>
                    <th style="width: 10%; text-align: center;">OFF</th>
                </tr>
                <tr>
                    <td>Control</td>
                    <td style="text-align: center;">{{ $registro->control == 'M' ? 'X' : '' }}</td>
                    <td style="text-align: center;">{{ $registro->control == 'A' ? 'X' : '' }}</td>
                    <td style="text-align: center;">{{ $registro->control == 'OFF' ? 'X' : '' }}</td>
                </tr>
                <tr>
                    <td>Transferencia</td>
                    <td style="text-align: center;">{{ $registro->transferencia == 'M' ? 'X' : '' }}</td>
                    <td style="text-align: center;">{{ $registro->transferencia == 'A' ? 'X' : '' }}</td>
                    <td style="text-align: center;">{{ $registro->transferencia == 'OFF' ? 'X' : '' }}</td>
                </tr>
                <tr>
                    <td>Cargador</td>
                    <td style="text-align: center;">{{ $registro->posicion_cargador == 'ON' ? 'X' : '' }}</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">{{ $registro->posicion_cargador == 'OFF' ? 'X' : '' }}</td>
                </tr>
                <tr>
                    <td>Totalizador</td>
                    <td style="text-align: center;">{{ $registro->totalizador == 'ON' ? 'X' : '' }}</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">{{ $registro->totalizador == 'OFF' ? 'X' : '' }}</td>
                </tr>
                <tr>
                    <td>Precalentador</td>
                    <td style="text-align: center;">{{ $registro->precalentador_posicion == 'ON' ? 'X' : '' }}</td>
                    <td style="text-align: center;">&nbsp;</td>
                    <td style="text-align: center;">{{ $registro->precalentador_posicion == 'OFF' ? 'X' : '' }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px;" class="section-title">FOTOS DESPUÉS</div>
        @if ($registro->foto_uno_despues || $registro->foto_dos_despues || $registro->foto_tres_despues)
        <div style="margin-top: 10px;" class="images">
            @if ($registro->foto_uno_despues)
            <div style="display: inline-block; text-align: center; margin-right: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_uno_despues) }}" alt="Foto 1">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_uno_despues }}</p>
            </div>
            @endif
            @if ($registro->foto_dos_despues)
            <div style="display: inline-block; text-align: center; margin-right: 5px;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_dos_despues) }}" alt="Foto 2">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_dos_despues }}</p>
            </div>
            @endif
            @if ($registro->foto_tres_despues)
            <div style="display: inline-block; text-align: center;">
                <img style="width: 232px; height: 174px;" src="{{ public_path('uploads/' . $registro->foto_tres_despues) }}" alt="Foto 3">
                <p style="font-size: 9px; margin-top: 2px;">{{ $registro->pie_foto_tres_despues }}</p>
            </div>
            @endif
        </div>
        @else
        <p>No hay fotos disponibles.</p>
        @endif

        <div class="section">
            <table>
                <tr>
                    <th>Llegada Tcnico</th>
                    <th>Salida Tcnico</th>
                </tr>
                <tr>
                    <td>
                        Día: {{ date('d', strtotime($registro->llegada_tecnico)) }}<br>
                        Mes: {{ date('m', strtotime($registro->llegada_tecnico)) }}<br>
                        Ao: {{ date('Y', strtotime($registro->llegada_tecnico)) }}<br>
                        Hora: {{ date('H:i A', strtotime($registro->llegada_tecnico)) }}
                    </td>
                    <td>
                        Día: {{ date('d', strtotime($registro->salida_tecnico)) }}<br>
                        Mes: {{ date('m', strtotime($registro->salida_tecnico)) }}<br>
                        Año: {{ date('Y', strtotime($registro->salida_tecnico)) }}<br>
                        Hora: {{ date('H:i A', strtotime($registro->salida_tecnico)) }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <table>
                <tr>
                    <th>Calificación del Servicio (por parte del cliente)</th>
                    
                </tr>
                <tr>
                    <td>
                        <label class="checkbox">{{ $registro->calificacion_servicio == 'Bueno' ? 'X' : '' }}</label> Bueno
                        <label class="checkbox">{{ $registro->calificacion_servicio == 'Regular' ? 'X' : '' }}</label> Regular
                        <label class="checkbox">{{ $registro->calificacion_servicio == 'Malo' ? 'X' : '' }}</label> Malo
                    </td>
                    
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px !important" class="section">
            <table>
                <tr>
                    <th>Firma Tecnico</th>
                    <th>Firma Cliente</th>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top;">
                        <img src="{{ $registro->firma_tecnico }}" alt="Firma del Tecnico" style="width: 70%; height: auto; display: block; margin: 0 auto;">
                        <div style="margin-top: 10px;">
                            <p style="margin: 0;">Nombre Tcnico: {{ $registro->nombre_tecnico }}</p>
                            <p style="margin: 0;">Cédula Tcnico: {{ $registro->cedula_tecnico }}</p>
                            <p style="margin: 0;">Firma Técnico</p>
                        </div>
                    </td>
                    <td style="text-align: center; vertical-align: top;">
                        <img src="{{ $registro->firma_cliente }}" alt="Firma del Cliente" style="width: 70%; height: auto; display: block; margin: 0 auto;">
                        <div style="margin-top: 10px;">
                            <p style="margin: 0;">Nombre Cliente: {{ $registro->nombre_cliente }}</p>
                            <p style="margin: 0;">Cédula Cliente: {{ $registro->cedula_cliente }}</p>
                            <p style="margin: 0;">Firma Cliente</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- <div class="firma-section">
            <div class="firma">
                <p>Nombre Técnico: {{ $registro->nombre_tecnico }}</p>
                <p>Cédula Técnico: {{ $registro->cedula_tecnico }}</p>
                <p>Firma Técnico</p>
            </div>
            <div class="firma">
                <p>Nombre Cliente: {{ $registro->nombre_cliente }}</p>
                <p>Cdula Cliente: {{ $registro->cedula_cliente }}</p>
                <p>Firma Cliente</p>
            </div>
        </div> --}}

        <div class="footer">
            <p>Carrera 83 No. 72B 06 Tel: (571) 694 9133 / 694 9128 / 694 9125 www.genservices.com.co Bogotá, D.C. - Colombia</p>
            <p>Las partes interesadas que intervienen en este informe, suscriben el presente 
            COMPROMISO DE CONFIDENCIALIDAD Y NO DIVULGACIN DE LA INFORMACIN, 
            que está clasificada como reservada, en la entrega del informe, esta únicamente será 
            compartida con las sucursales, empresas y personas autorizadas para el desarrollo del 
            servicio. Las partes interesadas cumplirán los requisitos legales y reglamentarios 
            relacionados con las poltica de confidencialidad de la Empresa GEN SERVICES S.A.S., 
            por lo tanto, no se emitirn copias, divulgaciones o reproducciones a tercero, 
            contenidas en el sistema.</p>
        </div>
    </div>
</body>
</html>
