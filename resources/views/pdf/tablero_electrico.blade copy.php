<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tablero Electrico</title>
    <link rel="stylesheet" href="{{ public_path('bootstrap.css') }}">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }
        .logo, .company-details, .section-title, .footer {
            text-align: center;
        }
        .section-title {
            font-size: 16px;
            margin: 20px 0;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 3px;
            border-radius: 5px;
        }
        img {
            width: 100px; /* Ajusta según la necesidad */
            height: auto;
            margin-bottom: 3px;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
        }
        h1, h2, h3, div {
            color: #156e44 !important;
        }
        
        .tamano-imagenes {
            width: 200px !important;
            height: 200px !important;
            objet-fit: cover !important;
            background-color: #db001228;
        }
    </style>
</head>
<body>

    <header>
        <div style="float: left; width: 25%;">
            <img src="{{ public_path('logo_empresa.png') }}" alt="Logo Empresa" style="width: 200px; height: auto;">
            <p style="font-size: 8px;"><b>CONFIABILIDAD, CALIDAD Y CUMPLIMIENTO</b></p>
        </div>
        <div style="float: right; width: 75%; text-align: right;">
            <strong>GEN SERVICES</strong><br>
            <b>NIT: 900748881-8</b><br>
            Fecha del Informe: {{ $registro->created_at->format('d/m/Y') }}<br>
            <b>Dirección de la Empresa:</b><br>
            Carrera 83 # 72B-06, Bogotá D.C.<br>
            <b>Datos de contacto:</b><br>
            ejemplo@mail.com<br>
            Teléfonos: (+57) 300 0000000<br>
            (+57) 300 0000000
        </div>
        <div style="clear: both;"></div>
    </header>
    
<div class="section-title"><h1 style="font-size: 25px;"><b>HOJA DE SERVICIO TECNICO PARA TABLEROS ELECTRICOS</b></h1></div>

<div style="text-align: left !important;" class="section-title">CLIENTE</div>
<table>
    <tr>
        <th>NOMBRE</th>
        <td>{{ $registro->user->name }}</td>
        <th>SUCURSAL</th>
        <td>{{ $registro->sucursal->nombre_sucursal }}</td>
    </tr>
    <tr>
        <th>QUIÉN SOLICITA</th>
        <td>{{ $registro->quien_recibe }}</td>
        <th>CONTACTO</th>
        <td>{{ $registro->contacto }}</td>
    </tr>
    <tr>
        <th>UBICACIÓN</th>
        <td>{{ $registro->ubicacion }}</td>
        <th>CORREO ELECTRÓNICO</th>
        <td>{{ $registro->mail }}</td>
    </tr>
    <tr>
        <th>ACTIVIDAD</th>
        <td colspan="3">{{ $registro->actividad }}</td>
    </tr>
</table>
</div>

<div style="text-align: left !important;" class="section-title">DATOS DEL EQUIPO</div>
<table>
    <tr>
        <th>MODELO DEL MOTOR</th>
        <td>{{ $registro->modelo_motor }}</td>
        <th>SERIE DEL MOTOR</th>
        <td>{{ $registro->serie_motor }}</td>
    </tr>
    <tr>
        <th>MARCA DEL MOTOR</th>
        <td>{{ $registro->marca_motor }}</td>
        <th>MODELO DEL GENERADOR</th>
        <td>{{ $registro->modelo_generador }}</td>
    </tr>
    <tr>
        <th>SERIE DEL GENERADOR</th>
        <td>{{ $registro->serie_generador }}</td>
        <th>MARCA DEL GENERADOR</th>
        <td>{{ $registro->marca_generador }}</td>
    </tr>
    <tr>
        <th>MODELO DE LA PLANTA</th>
        <td>{{ $registro->modelo_planta }}</td>
        <th>SERIE DE LA PLANTA</th>
        <td>{{ $registro->serie_planta }}</td>
    </tr>
    <tr>
        <th>MARCA DE LA PLANTA</th>
        <td>{{ $registro->marca_planta }}</td>
        <th>HORAS DEL MOTOR</th>
        <td>{{ $registro->horas_motor }}</td>
    </tr>
    <tr>
        <th>POTENCIA</th>
        <td>{{ $registro->potencia }}</td>
        <th>CONTROL</th>
        <td>{{ $registro->control }}</td>

    </tr>
</table>
</div>

<div style="text-align: left !important; margin-bottom: 40px;" class="section-title">IMÁGENES DEL SERVICIO</div>
<div class="images">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_uno) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_dos) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_tres) }}" alt="Imagen" style="width: 33%; height: auto;">
    {{-- <img src="{{ public_path($registro->foto_uno) }}" alt="Foto 1">
    <img src="{{ public_path($registro->foto_dos) }}" alt="Foto 2">
    <img src="{{ public_path($registro->foto_tres) }}" alt="Foto 3"> --}}
    <!-- Continuar agregando imágenes según sea necesario -->
</div>
</div>

<div style="text-align: left !important;" class="section-title">PARAMETROS DE FUNCIONAMIENTO</div>
<table>
    <tr>
        <th>TEMPERATURA</th>
        <td>{{ $registro->temperatura }}</td>
        <th>VOLTAJE L1 - L2</th>
        <td>{{ $registro->voltaje_l1_l2 }}</td>
        <th>AMP LINEA 1</th>
        <td>{{ $registro->amp_linea_1 }}</td>
    </tr>
    <tr>
        <th>PRESION DE ACEITE</th>
        <td>{{ $registro->presion_aceite }}</td>
        <th>VOLTAJE L2 - L3</th>
        <td>{{ $registro->voltaje_l2_l3 }}</td>
        <th>AMP LINEA 2</th>
        <td>{{ $registro->amp_linea_2 }}</td>
    </tr>
    <tr>
        <th>FRECUENCIA</th>
        <td>{{ $registro->frecuencia }}</td>
        <th>VOLTAJE L1 - L3</th>
        <td>{{ $registro->voltaje_l1_l3 }}</td>
        <th>AMP Linea 3</th>
        <td>{{ $registro->amp_linea_3 }}</td>
    </tr>
</table>
</div>

<div style="text-align: left !important; margin-bottom: 40px;" class="section-title">IMÁGENES DEL SERVICIO</div>
<div class="images">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_uno) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_dos) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_tres) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_cuatro) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_cinco) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_seis) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_siete) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_param_ocho) }}" alt="Imagen" style="width: 33%; height: auto;">
    {{-- <img src="{{ public_path('storage/' . $registro->foto_uno) }}" alt="Imagen" style="width: 100px; height: auto;"> --}}
    {{-- <img src="{{ public_path($registro->foto_uno) }}" alt="Foto 1">
    <img src="{{ public_path($registro->foto_dos) }}" alt="Foto 2">
    <img src="{{ public_path($registro->foto_tres) }}" alt="Foto 3"> --}}
    <!-- Continuar agregando imágenes según sea necesario -->
</div>
</div>

<div style="text-align: left !important;" class="section-title">CHECK LIST</div>
<table>
    <tr>
        <th>ESTADO DE ACEITE</th>
        <td>{{ $registro->estado_aceite }}</td>
        <th>NIVEL DE ACEITE</th>
        <td>{{ $registro->nivel_aceite }}</td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO DE RADIADOR</th>
        <td>{{ $registro->estado_radiador }}</td>
        <th>NIVEL DE REFRIGERANTE</th>
        <td>{{ $registro->nivel_refrigerante }}</td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO DE BATERIAS</th>
        <td>{{ $registro->estado_baterias }}</td>
        <th>MEDIDA VOLTAJE BATERIA</th>
        <td>{{ $registro->medida_voltaje_bateria }}</td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO DE CARGADOR</th>
        <td>{{ $registro->cargador }}</td>
        <th>MEDIDA DEL CARGADOR</th>
        <td>{{ $registro->medida_del_cargador }}</td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO DE CORREAS</th>
        <td>{{ $registro->estado_correas }}</td>
        <th></th>
        <td></td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO FILTRACION</th>
        <td>{{ $registro->estado_filtracion }}</td>
        <th></th>
        <td></td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO DE MANGUERAS</th>
        <td>{{ $registro->estado_mangueras }}</td>
        <th></th>
        <td></td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO DE PRECALENTADOR</th>
        <td>{{ $registro->estado_precalentador }}</td>
        <th>CONEXIÓN DE PRECALENTADOR</th>
        <td>{{ $registro->conexion_precalentador }}</td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO TANQUE COMBUSTIBLE</th>
        <td>{{ $registro->estado_tanque_combustible }}</td>
        <th>NIVEL DE COMBUSTIBLE</th>
        <td>{{ $registro->nivel_de_combustible }}</td>
        <th>TIPO DE TANQUE</th>
        <td>{{ $registro->tipo_de_tanque }}</td>
    </tr>
    <tr>
        <th>ESTADO DE GENERADOR</th>
        <td>{{ $registro->estado_generador }}</td>
        <th></th>
        <td></td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO DE CONEXIONES</th>
        <td>{{ $registro->estado_conexiones }}</td>
        <th></th>
        <td></td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO CUARTO/CABINA</th>
        <td>{{ $registro->estado_cuarto_o_cabina }}</td>
        <th></th>
        <td></td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO MOTOR ARRANQUE</th>
        <td>{{ $registro->estado_motor_de_arranque }}</td>
        <th>CAÍDA VOLTAJE BATERIAS</th>
        <td>{{ $registro->caida_de_voltaje_baterias }}</td>
        <th></th>
        <td></td>
    </tr>
    <tr>
        <th>ESTADO ALTERNADOR</th>
        <td>{{ $registro->estado_alternador }}</td>
        <th>VOLTAJE DEL ALTERNADOR</th>
        <td>{{ $registro->voltaje_del_alternador }}</td>
        <th></th>
        <td></td>
    </tr>
</table>
</div>

<div style="text-align: left !important; margin-bottom: 40px;" class="section-title">IMÁGENES DEL SERVICIO</div>
<div class="images">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_uno) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_dos) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_tres) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_cuatro) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_cinco) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_seis) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_siete) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_check_ocho) }}" alt="Imagen" style="width: 33%; height: auto;">
    {{-- <img src="{{ public_path('storage/' . $registro->foto_uno) }}" alt="Imagen" style="width: 100px; height: auto;"> --}}
    {{-- <img src="{{ public_path($registro->foto_uno) }}" alt="Foto 1">
    <img src="{{ public_path($registro->foto_dos) }}" alt="Foto 2">
    <img src="{{ public_path($registro->foto_tres) }}" alt="Foto 3"> --}}
    <!-- Continuar agregando imágenes según sea necesario -->
</div>
</div>

<div style="text-align: left !important;" class="section-title">PRUEBAS Y ENTREGA</div>
<table>
    <tr>
        <th>SIMULACIÓN FALLA TEMPERATURA</th>
        <td>{{ $registro->simulacion_falla_temperatura }}</td>
        <th>PRUEBAS EN VACÍO</th>
        <td>{{ $registro->pruebas_en_vacio }}</td>
        <th>POSICION DEL BRAKER</th>
        <td>{{ $registro->posicion_del_braker }}</td>
    </tr>
    <tr>
        <th>SIMULACIÓN FALLA PRESIN</th>
        <td>{{ $registro->simulacion_falla_presion }}</td>
        <th>PRUEBAS CON CARGA</th>
        <td>{{ $registro->pruebas_con_carga }}</td>
        <th>POSICIÓN SWITCH CONTROL</th>
        <td>{{ $registro->posicion_switch_control }}</td>
    </tr>
    <tr>
        <th>SIMULACIÓN FALLA LCL</th>
        <td>{{ $registro->simulacion_falla_lcl }}</td>
        <th></th>
        <td></td>
        <th>POSICIÓN SWITCH CARGADOR</th>
        <td>{{ $registro->posicion_switch_cargador }}</td>
    </tr>
</table>
</div>

<div style="text-align: left !important; margin-bottom: 40px;" class="section-title">IMGENES DEL SERVICIO</div>
<div class="images">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_prueba_uno) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_prueba_dos) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_prueba_tres) }}" alt="Imagen" style="width: 33%; height: auto;">
    {{-- <img src="{{ public_path('storage/' . $registro->foto_uno) }}" alt="Imagen" style="width: 100px; height: auto;"> --}}
    {{-- <img src="{{ public_path($registro->foto_uno) }}" alt="Foto 1">
    <img src="{{ public_path($registro->foto_dos) }}" alt="Foto 2">
    <img src="{{ public_path($registro->foto_tres) }}" alt="Foto 3"> --}}
    <!-- Continuar agregando imágenes según sea necesario -->
</div>

<div class="section-title">ACTIVIDADES REALIZADAS</div>
<table>
    <tr>
        <th style="text-align: center; font-weight: 400 !important;">{{ $registro->actividades_realizadas }}</th>
    </tr>
</table>


<div style="text-align: left !important; margin-bottom: 40px;" class="section-title">IMÁGENES DEL SERVICIO</div>
<div class="images">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_uno) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_dos) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_tres) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_cuatro) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_cinco) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_seis) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_siete) }}" alt="Imagen" style="width: 33%; height: auto;">
    <img class="tamano-imagenes" src="{{ public_path('storage/' . $registro->foto_final_ocho) }}" alt="Imagen" style="width: 33%; height: auto;">
    {{-- <img src="{{ public_path('storage/' . $registro->foto_uno) }}" alt="Imagen" style="width: 100px; height: auto;"> --}}
    {{-- <img src="{{ public_path($registro->foto_uno) }}" alt="Foto 1">
    <img src="{{ public_path($registro->foto_dos) }}" alt="Foto 2">
    <img src="{{ public_path($registro->foto_tres) }}" alt="Foto 3"> --}}
    <!-- Continuar agregando imágenes según sea necesario -->
</div>

<div style="text-align: left !important;" class="section-title">Recomendaciones</div>
<table>
    <tr>
        <th style="text-align: center; font-weight: 400 !important">{{ $registro->recomendaciones }}</th>
    </tr>
</table>


<div style="text-align: left !important;" class="section-title">FIRMAS</div>

{{-- <table>
    <tr>
        <th>Firma del Técnico</th>
        <td>
            <img src="{{ $registro->firma_tecnico }}" alt="Firma del Tecnico" style="width: 200px; height: auto;">
            <!-- Espacio para la firma del técnico o insertar imagen de la firma si est disponible -->
        </td>
    </tr>
    <tr>
        <th>Firma del Cliente</th>
        <td>
            <img src="{{ $registro->firma_cliente }}" alt="Firma del Cliente" style="width: 200px; height: auto;">
            <!-- Espacio para la firma del cliente o insertar imagen de la firma si está disponible -->
        </td>
    </tr>
</table> --}}

<div class="container">
    <table class="table">
      <tbody>
        <tr style="">
          <!-- Aquí irían las imágenes de las firmas -->
          <td style="border: none !important;" class="text-center"><img src="{{ $registro->firma_tecnico }}" alt="Firma del Tecnico" style="width: 100%; height: auto;"></td>
          <td style="border: none !important;" class="text-center"><img src="{{ $registro->firma_cliente }}" alt="Firma del Cliente" style="width: 100%; height: auto;"></td>
        </tr>
        <tr>
          <!-- Títulos debajo de las imágenes -->
          <td style="border-bottom: none !important; border-left: none !important; border-right: none !important;" class="text-center">Firma del Técnico</td>
          <td style="border-bottom: none !important; border-left: none !important; border-right: none !important;" class="text-center">Firma del Cliente</td>
        </tr>
        <tr>
            <!-- Títulos debajo de las imágenes -->
            <td style="border: none !important" class="text-center">Nombre: {{ $registro->nombre_tecnico }}</td>
            <td style="border: none !important" class="text-center">Nombre: {{ $registro->nombre_cliente }}</td>
        </tr>
        <tr>
            <!-- Títulos debajo de las imágenes -->
            <td style="border: none !important" class="text-center">Cédula: {{ $registro->cedula_tecnico }}</td>
            <td style="border: none !important" class="text-center">Cédula: {{ $registro->cedula_cliente }}</td>
        </tr>
      </tbody>
    </table>
  </div>

</body>
</html>
