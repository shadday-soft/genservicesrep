<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe de Servicio</title>
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        img {
            width: 100px; /* Ajusta según la necesidad */
            height: auto;
        }
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <div style="float: left; width: 20%;">
            <img src="{{ public_path('logo_empresa.png') }}" alt="Logo Empresa" style="width: 100px; height: auto;">
        </div>
        <div style="float: right; width: 75%; text-align: right;">
            <strong>Tu Negocio WB</strong><br>
            <b>NIT: 6545636</b><br>
            Fecha del Informe: {{ $registro->created_at->format('d/m/Y') }}<br>
            Dirección de la Empresa<br>
            Info@tunegociowb@gmail.com<br>
            Teléfono: +57 310 2093700
        </div>
        <div style="clear: both;"></div>
    </header>

<div class="section-title">Datos del Cliente y del Servicio</div>


<div class="section-title">Datos del Equipo</div>
<table>
    <tr>
        <th>Modelo del Motor</th>
        <td>{{ $registro->modelo_motor }}</td>
        <th>Serie del Motor</th>
        <td>{{ $registro->serie_motor }}</td>
    </tr>
    <tr>
        <th>Marca del Motor</th>
        <td>{{ $registro->marca_motor }}</td>
        <th>Modelo del Generador</th>
        <td>{{ $registro->modelo_generador }}</td>
    </tr>
    <tr>
        <th>Serie del Generador</th>
        <td>{{ $registro->serie_generador }}</td>
        <th>Marca del Generador</th>
        <td>{{ $registro->marca_generador }}</td>
    </tr>
    <tr>
        <th>Modelo de la Planta</th>
        <td>{{ $registro->modelo_planta }}</td>
        <th>Serie de la Planta</th>
        <td>{{ $registro->serie_planta }}</td>
    </tr>
    <tr>
        <th>Marca de la Planta</th>
        <td>{{ $registro->marca_planta }}</td>
        <th>Horas del Motor</th>
        <td>{{ $registro->horas_motor }}</td>
    </tr>
    <tr>
        <th>Potencia</th>
        <td colspan="3">{{ $registro->potencia }}</td>
    </tr>
</table>

<div class="section-title">Detalles de Filtros y Componentes</div>
<table>
    <tr>
        <th>Filtro de Aceite Primario</th>
        <td>{{ $registro->filtro_aceite_primario }}</td>
        <th>Referencia</th>
        <td>{{ $registro->ref_filtro_aceite_primario }}</td>
    </tr>
    <tr>
        <th>Cantidad</th>
        <td>{{ $registro->cant_filtro_aceite_primario }}</td>
        <th>Filtro de Aceite Secundario</th>
        <td>{{ $registro->filtro_aceite_secundario }}</td>
</tr>
<tr>
<th>Referencia</th>
<td>{{ $registro->ref_filtro_aceite_secundario }}</td>
<th>Cantidad</th>
<td>{{ $registro->cant_filtro_aceite_secundario }}</td>
</tr>
<tr>
<th>Filtro de Combustible Primario</th>
<td>{{ $registro->filtro_combustible_prim }}</td>
<th>Referencia</th>
<td>{{ $registro->ref_filtro_combustible_prim }}</td>
</tr>
<tr>
<th>Cantidad</th>
<td>{{ $registro->cant_filtro_combustible_prim }}</td>
<th>Filtro de Combustible Secundario</th>
<td>{{ $registro->filtro_combustible_sec }}</td>
</tr>
<tr>
<th>Referencia</th>
<td>{{ $registro->ref_filtro_combustible_sec }}</td>
<th>Cantidad</th>
<td>{{ $registro->cant_filtro_combustible_sec }}</td>
</tr>
<!-- Agregar más filas según sea necesario para otros filtros y componentes -->

</table>
<div class="section-title">Imágenes del Servicio</div>
<div class="images">
    {{-- <img src="{{ public_path('storage/' . $registro->foto_uno) }}" alt="Imagen" style="width: 100px; height: auto;"> --}}
    {{-- <img src="{{ public_path($registro->foto_uno) }}" alt="Foto 1">
    <img src="{{ public_path($registro->foto_dos) }}" alt="Foto 2">
    <img src="{{ public_path($registro->foto_tres) }}" alt="Foto 3"> --}}
    <!-- Continuar agregando imágenes según sea necesario -->
</div>
<div class="section-title">Observaciones</div>
<p>{{ $registro->observaciones }}</p>
<div class="section-title">Firmas</div>
{{-- <table>
    <tr>
        <th>Firma del Técnico</th>
        <td>
            <img src="{{ $registro->firma_tecnico }}" alt="Firma del Tecnico" style="width: 200px; height: auto;">
            <!-- Espacio para la firma del técnico o insertar imagen de la firma si está disponible -->
        </td>
    </tr>
    <tr>
        <th>Firma del Cliente</th>
        <td>
            <img src="{{ $registro->firma_cliente }}" alt="Firma del Cliente" style="width: 200px; height: auto;">
            <!-- Espacio para la firma del cliente o insertar imagen de la firma si está disponible -->
        </td>
    </tr>
</table>
-------------------------------------------------------------------------------------------------
<table>
    <tr>
        <th>Firma del Técnico</th>
        <td>
            <img src="{{ $registro->firma_tecnico }}" alt="Firma del Tecnico" style="width: 200px; height: auto;">
            <!-- Espacio para la firma del técnico o insertar imagen de la firma si está disponible -->
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
        <tr>
          <!-- Aquí irían las imágenes de las firmas -->
          <td class="text-center"><img src="{{ $registro->firma_tecnico }}" alt="Firma del Tecnico" style="width: 100%; height: auto;"></td>
          <td class="text-center"><img src="{{ $registro->firma_cliente }}" alt="Firma del Cliente" style="width: 100%; height: auto;"></td>
        </tr>
        <tr>
          <!-- Títulos debajo de las imágenes -->
          <td class="text-center">Firma del Técnico</td>
          <td class="text-center">Firma del Cliente</td>
        </tr>
      </tbody>
    </table>
  </div>
<footer class="footer">
    Página {PAGE_NUM} de {PAGE_COUNT}
</footer>

</body>
</html>
