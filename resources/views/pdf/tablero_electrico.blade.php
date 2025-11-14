<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Tablero Eléctrico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header, .footer {
            text-align: center;
            left: 0;
            right: 0;
        }
        .header {
            top: 0;
        }
        .footer {
            bottom: 0;
            font-size: 8px;
        }
        .section-title {
            font-size: 16px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
            color: #000000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000000a8;
            padding: 3px;
            text-align: left;
        }
        th {
            background-color: #db001228;
            text-transform: uppercase;
        }
        img {
            width: 150px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .firma {
            width: 200px;
            height: auto;
        }
        .corporate-color {
            color: #000000;
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
            DIR. CRA 83 #72B-06<br>
            Bogotá<br>
            Colombia<br>
            <b>Datos de contacto:</b><br>
            genservices@outlook.com<br>
            TEL. 6949133<br>
            CEL. 3005231475
        </div>
        <div style="float: right; width: 25%;">
            <img src="{{ public_path('logo_empresa.png') }}" alt="Logo Empresa" style="width: 200px; height: auto;">
            <p style="font-size: 8px; text-align: right !important;"><b>CONFIABILIDAD, CALIDAD Y CUMPLIMIENTO</b></p>
        </div>
        
        <div style="clear: both;"></div>
    </header>

    <div class="section-title"><h1 style="font-size: 20px;"><b>HOJA DE SERVICIO TECNICO PARA TABLEROS ELECTRICOS</b></h1></div>

<main>
    <div class="section-title">DATOS DEL CLIENTE</div>
    <table>
        <tr>
            <th>Número de Orden</th>
            <td>{{ $registro->numero_orden }}</td>
            <th>Número de Solicitud</th>
            <td>{{ $registro->numero_solicitud }}</td>
        </tr>
        <tr>
            <th>Asignar a Técnico</th>
            <td>{{ $registro->user->name }}</td>
            <th>Empresa</th>
            <td>{{ $registro->user->name }}</td>
        </tr>
        <tr>
            <th>¿Quién solicita?</th>
            <td>{{ $registro->quiensolicita_id }}</td>
            <th>Telfono</th>
            <td>{{ $registro->telefono }}</td>
        </tr>
        <tr>
            <th>Correo Electrónico</th>
            <td>{{ $registro->mail }}</td>
            <th>Ubicación</th>
            <td>{{ $registro->ubicacion }}</td>
        </tr>
        <tr>
            <th>Sucursal</th>
            <td>{{ $registro->sucursal->nombre_sucursal }}</td>
            <th>Equipo</th>
            <td>{{ $registro->equipo->nombre }}</td>
        </tr>
        <tr>
            <th>Tipo de Tablero</th>
            <td colspan="3">{{ $registro->tipo_tablero }}</td>
        </tr>
    </table>

    <div class="section-title">DATOS DEL EQUIPO</div>
    <table>
        <tr>
            <th>Tensión de Operación</th>
            <td>{{ $registro->tension_operacion }}</td>
            <th>Corriente Nominal</th>
            <td>{{ $registro->corriente_nominal }}</td>
        </tr>
        <tr>
            <th>Elemento de Maniobra</th>
            <td>{{ $registro->elemento_maniobra }}</td>
            <th>Fabricante</th>
            <td>{{ $registro->fabricante }}</td>
        </tr>
        <tr>
            <th>Tipo de Aplicación</th>
            <td>{{ $registro->tipo_aplicacion }}</td>
            <th>Control ATS</th>
            <td>{{ $registro->control_ats }}</td>
        </tr>
        <tr>
            <th>Tipo de Servicio</th>
            <td colspan="3">{{ $registro->tipo_servicio }}</td>
        </tr>
    </table>

    <div class="section-title">OBSERVACIONES INICIALES</div>
    <!--<p style="text-align: center">{!! $registro->observaciones_iniciales !!}</p>-->
    <table>    
        <tr>
            <td style="text-align: center;" colspan="3">{!! $registro->observaciones_iniciales !!}</td>
        </tr>
    </table>

    <div class="section-title">CHECK LIST</div>
    <table>
        <tr>
            <th>Gabinete</th>
            <td>{{ $registro->gabinete }}</td>
            <th>Puertas</th>
            <td>{{ $registro->puertas }}</td>
        </tr>
        <tr>
            <th>Cerraduras</th>
            <td>{{ $registro->cerraduras }}</td>
            <th>Bisagras</th>
            <td>{{ $registro->bisagras }}</td>
        </tr>
        <tr>
            <th>Limpieza General</th>
            <td>{{ $registro->limpieza_general }}</td>
            <th>Pilotos Indicadores</th>
            <td>{{ $registro->pilotos_indicadores }}</td>
        </tr>
        <tr>
            <th>Selectores</th>
            <td>{{ $registro->selectores }}</td>
            <th>Relés</th>
            <td>{{ $registro->reles }}</td>
        </tr>
        <tr>
            <th>Temporizadores</th>
            <td>{{ $registro->temporizadores }}</td>
            <th>Contactores</th>
            <td>{{ $registro->contactores }}</td>
        </tr>
        <tr>
            <th>Interruptores</th>
            <td>{{ $registro->interruptores }}</td>
            <th>Conexiones de Control</th>
            <td>{{ $registro->conexiones_control }}</td>
        </tr>
        <tr>
            <th>Conexiones de Potencia</th>
            <td>{{ $registro->conexiones_potencia }}</td>
            <th>Barraje de Potencia</th>
            <td>{{ $registro->barraje_potencia }}</td>
        </tr>
        <tr>
            <th>Barraje de Neutros</th>
            <td>{{ $registro->barraje_neutros }}</td>
            <th>Barraje de Tierras</th>
            <td>{{ $registro->barraje_tierras }}</td>
        </tr>
        <tr>
            <th>PLC</th>
            <td>{{ $registro->plc }}</td>
            <th>ATS</th>
            <td>{{ $registro->ats }}</td>
        </tr>
        <tr>
            <th>Fuentes Auxiliares</th>
            <td>{{ $registro->fuentes_auxiliares_check }}</td>
            <th>Capacitores</th>
            <td>{{ $registro->capacitores }}</td>
        </tr>
        <tr>
            <th>Analizador de Red</th>
            <td colspan="3">{{ $registro->analizador_de_red }}</td>
        </tr>
    </table>

    <div style="margin-top: 30px;" class="section-title">FOTOS ESTADO INICIAL</div>
        <div style="margin-top: 100px;" class="images">
            <img style="width: 218px;" src="{{ public_path('storage/' . $registro->Foto_uno_antes) }}" alt="Foto 1">
            <img style="width: 218px;" src="{{ public_path('storage/' . $registro->Foto_dos_antes) }}" alt="Foto 2">
            <img style="width: 218px;" src="{{ public_path('storage/' . $registro->Foto_tres_antes) }}" alt="Foto 3">
        </div>

    <div class="section-title">ACTIVIDAD REALIZADA</div>
    <!--<p style="text-align: center">{!! $registro->actividad_realizada !!}</p>-->
    <table>    
        <tr>
            <td style="text-align: center;" colspan="3">{!! $registro->actividad_realizada !!}</td>
        </tr>
    </table>

    <div class="section-title">PRUEBAS CON EL EQUIPO EN OPERACIÓN</div>
    <table>
        <tr>
            <th>Segundos TDES</th>
            <td>{{ $registro->segundos_tdes }}</td>
            <th>Segundos TDNE</th>
            <td>{{ $registro->segundos_tdne }}</td>
        </tr>
        <tr>
            <th>Segundos TDTP</th>
            <td>{{ $registro->segundos_tdtp }}</td>
            <th>Segundos TDEN</th>
            <td>{{ $registro->segundos_tden }}</td>
        </tr>
        <tr>
            <th>Segundos TDEC</th>
            <td colspan="3">{{ $registro->segundos_tdec }}</td>
        </tr>
    </table>

    <div class="section-title">AJUSTES</div>
    <table>
        <tr>
            <th>Alto Voltaje</th>
            <td>{{ $registro->alto_voltaje }}</td>
            <th>Bajo Voltaje</th>
            <td>{{ $registro->bajo_voltaje }}</td>
        </tr>
        <tr>
            <th>Alta Frecuencia</th>
            <td>{{ $registro->alta_frecuencia }}</td>
            <th>Baja Frecuencia</th>
            <td>{{ $registro->baja_frecuencia }}</td>
        </tr>
        <tr>
            <th>Sobre Carga</th>
            <td>{{ $registro->sobre_carga }}</td>
            <th>Sobre Corriente</th>
            <td>{{ $registro->sobre_corriente }}</td>
        </tr>
    </table>

    <div class="section-title">TEMPERATURA</div>
    <table>
        <tr>
            <th>Cables de Potencia</th>
            <td>{{ $registro->cables_potencia }}</td>
            <th>Terminales</th>
            <td>{{ $registro->terminales }}</td>
        </tr>
        <tr>
            <th>Cuerpo de Contactores</th>
            <td>{{ $registro->cuepo_contactores }}</td>
            <th>Cuerpo de Interruptores</th>
            <td>{{ $registro->cuerpo_interruptores }}</td>
        </tr>
        <tr>
            <th>Transformadores</th>
            <td>{{ $registro->transformadores }}</td>
            <th>Punto Más Caliente</th>
            <td>{{ $registro->punto_mas_caliente }}</td>
        </tr>
    </table>
    
    <div class="section-title">OBSERVACIONES PRUEBAS</div>
    <!--<p style="text-align: center">{{ $registro->observaciones_pruebas }}</p>-->
    <table>    
        <tr>
            <td style="text-align: center; padding: 15px;" colspan="3">{{ $registro->observaciones_pruebas }}</td>
        </tr>
    </table>

    <div class="section-title">POSICIÓN DE LOS INSTRUMENTOS AL CONCLUIR EL SERVICIO</div>
    <table>
        <tr>
            <th>Control</th>
            <td>{{ $registro->control }}</td>
            <th>Selector</th>
            <td>{{ $registro->selector }}</td>
        </tr>
        <tr>
            <th>Fuentes Auxiliares</th>
            <td colspan="3">{{ $registro->fuentes_auxiliares_posicion }}</td>
        </tr>
    </table>

    <div class="section-title">VOLTAJE</div>
    <table>
        <tr>
            <th>L1-N</th>
            <td>{{ $registro->l1_n }}</td>
            <th>L2-N</th>
            <td>{{ $registro->l2_n }}</td>
            <th>L3-N</th>
            <td>{{ $registro->l3_n }}</td>
        </tr>
    </table>

    <div class="section-title">FRECUENCIA</div>
    <table>
        <tr>
            <th>HZ</th>
            <td>{{ $registro->hz }}</td>
        </tr>
    </table>

    <div class="section-title">KW</div>
    <table>
        <tr>
            <th>L1</th>
            <td>{{ $registro->l1_kw }}</td>
            <th>L2</th>
            <td>{{ $registro->l2_kw }}</td>
            <th>L3</th>
            <td>{{ $registro->l3_kw }}</td>
            <th>AVG</th>
            <td>{{ $registro->avg_kw }}</td>
        </tr>
    </table>

    <div class="section-title">CORRIENTE</div>
    <table>
        <tr>
            <th>L1</th>
            <td>{{ $registro->l1_corriente }}</td>
            <th>L2</th>
            <td>{{ $registro->l2_corriente }}</td>
            <th>L3</th>
            <td>{{ $registro->l3_corriente }}</td>
        </tr>
    </table>

    <div class="section-title">FACTOR P</div>
    <table>
        <tr>
            <th>PF</th>
            <td>{{ $registro->pf }}</td>
        </tr>
    </table>

    <div class="section-title">KVA</div>
    <table>
        <tr>
            <th>L1</th>
            <td>{{ $registro->l1_kva }}</td>
            <th>L2</th>
            <td>{{ $registro->l2_kva }}</td>
            <th>L3</th>
            <td>{{ $registro->l3_kva }}</td>
            <th>AVG</th>
            <td>{{ $registro->avg_kva }}</td>
        </tr>
    </table>

    <div class="section-title">FOTOS DURANTE</div>
    @if ($registro->foto_uno_durante || $registro->foto_dos_durante || $registro->foto_tres_durante)
    
    <div style="margin-top: 100px;" class="images">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_uno_durante) }}" alt="Foto 1">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_dos_durante) }}" alt="Foto 2">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_tres_durante) }}" alt="Foto 3">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_cuatro_durante) }}" alt="Foto 4">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_cinco_durante) }}" alt="Foto 5">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_seis_durante) }}" alt="Foto 6">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_siete_durante) }}" alt="Foto 7">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_ocho_durante) }}" alt="Foto 8">
        <img style="width: 218px; margin-bottom: 2px;" src="{{ public_path('uploads/' . $registro->foto_nueve_durante) }}" alt="Foto 9">
    </div>
    @endif

    <div class="section-title">RECOMENDACIONES</div>
    <!--<p style="text-align: center">{{ $registro->recomendaciones }}</p>-->
    <table>    
        <tr>
            <td style="text-align: center; padding: 15px;" colspan="3">{{ $registro->recomendaciones }}</td>
        </tr>
    </table>

    <div class="section-title">FOTOS DESPUÉS</div>
    <div style="margin-top: 100px;" class="images">
        <img style="width: 218px;" src="{{ public_path('uploads/' . $registro->foto_uno_despues) }}" alt="Foto 1">
        <img style="width: 218px;" src="{{ public_path('uploads/' . $registro->foto_dos_despues) }}" alt="Foto 2">
        <img style="width: 218px;" src="{{ public_path('uploads/' . $registro->foto_tres_despues) }}" alt="Foto 3">
    </div>

    <div class="section-title">FIRMAS</div>
    <table>
        <tr>
            <th>Firma del Tcnico</th>
            <td><img src="{{ $registro->firma_tecnico }}" class="firma" alt="Firma del Técnico"></td>
            <th>Firma del Cliente</th>
            <td><img src="{{ $registro->firma_cliente }}" class="firma" alt="Firma del Cliente"></td>
        </tr>
        <tr>
            <th>Nombre del Técnico</th>
            <td>{{ $registro->nombre_tecnico }}</td>
            <th>Nombre del Cliente</th>
            <td>{{ $registro->nombre_cliente }}</td>
        </tr>
        <tr>
            <th>Cédula del Técnico</th>
            <td>{{ $registro->cedula_tecnico }}</td>
            <th>Cédula del Cliente</th>
            <td>{{ $registro->cedula_cliente }}</td>
        </tr>
    </table>

    <div class="section-title">FECHAS</div>
    <table>
        <tr>
            <th>Fecha de Solicitud</th>
            <td>Fecha: {{ $registro->fecha_solicitud }}</td>
            <th>Llegada del Tcnico</th>
            <td>{{ $registro->llegada_tecnico }}</td>
        </tr>
        <tr>
            <th>Salida del Técnico</th>
            <td colspan="3">{{ $registro->salida_tecnico }}</td>
        </tr>
        <tr>
            <th>Calificación del Servicio</th>
            <td colspan="3">{{ $registro->calificacion_servicio }}</td>
        </tr>
    </table>
</main>

<footer class="footer">
    <p>© {{ date('Y') }} GEN SERVICES. Todos los derechos reservados.</p>
    <p>Las partes interesadas que intervienen en este informe, suscriben el presente 
            COMPROMISO DE CONFIDENCIALIDAD Y NO DIVULGACIÓN DE LA INFORMACIÓN, 
            que está clasificada como reservada, en la entrega del informe, esta únicamente será 
            compartida con las sucursales, empresas y personas autorizadas para el desarrollo del 
            servicio. Las partes interesadas cumplirán los requisitos legales y reglamentarios 
            relacionados con las política de confidencialidad de la Empresa GEN SERVICES S.A.S., 
            por lo tanto, no se emitirán copias, divulgaciones o reproducciones a tercero, 
            contenidas en el sistema.
    </p>
</footer>

</body>
</html>
