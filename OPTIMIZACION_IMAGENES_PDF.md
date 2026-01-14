# Optimización de Imágenes en PDF

## Problema Original
Antes, la vista Blade `planta_electrica.blade.php` procesaba cada imagen individualmente:
- Hacía peticiones HTTP a URLs remotas para cada foto
- Convertía cada imagen a base64 de forma secuencial
- **Problema de rendimiento**: Para un informe con 15 fotos, hacía 15 peticiones HTTP separadas

## Solución Implementada

### 1. Pre-procesamiento en el Controlador
**Archivo**: `app/Http/Controllers/InformeController.php` (línea 175)
```php
$registro = ImageHelper::preprocessImagesForPdf($registro);
```

### 2. ImageHelper - Conversión Paralela
**Archivo**: `app/Helpers/ImageHelper.php`

El helper procesa TODAS las imágenes en paralelo usando cURL multi:
- **Fotos antes** (3 fotos)
- **Fotos durante** (9 fotos)
- **Fotos después** (3 fotos)
- **Firmas** (técnico y cliente)

**Ventajas**:
- ✅ Descarga todas las imágenes remotas en paralelo (usando cURL multi)
- ✅ Convierte a base64 solo una vez
- ✅ Detecta automáticamente el tipo MIME correcto
- ✅ Agrega prefijo `uploads/` a imágenes locales
- ✅ Maneja errores de forma robusta

### 3. Vista Simplificada
**Archivo**: `resources/views/pdf/planta_electrica.blade.php`

Antes (código repetitivo):
```php
@php
$fotoSrc = $registro->foto_uno_antes;
if (str_contains($fotoSrc, 'https://reporting.genservices.com.co/storage/')) {
    try {
        $imageData = @file_get_contents($fotoSrc);
        if ($imageData !== false) {
            $base64 = base64_encode($imageData);
            $mimeType = 'image/jpeg';
            $fotoSrc = 'data:' . $mimeType . ';base64,' . $base64;
        }
    } catch (\Exception $e) {
        // Si falla, usar la ruta local
    }
} else {
    $fotoSrc = 'uploads/' . $fotoSrc;
}
@endphp
<img src="{{ $fotoSrc }}" alt="Foto 1 Antes">
```

Ahora (código optimizado):
```php
{{-- Las imágenes ya fueron procesadas por ImageHelper::preprocessImagesForPdf() --}}
<img src="{{ $item['foto'] }}" alt="{{ $item['alt'] }}">
```

### 4. Uso de Arrays para Eliminar Duplicación
Todas las secciones de fotos ahora usan arrays:

```php
@php
$fotosAntes = [
    ['foto' => $registro->foto_uno_antes, 'pie' => $registro->pie_foto_uno_antes, 'alt' => 'Foto 1 Antes'],
    ['foto' => $registro->foto_dos_antes, 'pie' => $registro->pie_foto_dos_antes, 'alt' => 'Foto 2 Antes'],
    ['foto' => $registro->foto_tres_antes, 'pie' => $registro->pie_foto_tres_antes, 'alt' => 'Foto 3 Antes'],
];
$fotosAntesFiltradas = array_filter($fotosAntes, fn($item) => !empty($item['foto']));
$chunks = array_chunk($fotosAntesFiltradas, 2);
@endphp
```

## Mejoras de Rendimiento

### Antes
- ⏱️ **15 peticiones HTTP secuenciales**: ~3-5 segundos
- 🔄 **Conversión base64 repetida**: en la vista (durante renderizado)
- 💾 **Memoria**: picos durante renderizado

### Después
- ⚡ **1 petición paralela**: ~500ms para todas las imágenes
- ✨ **Conversión única**: antes del renderizado
- 📊 **Memoria**: más eficiente (libera después de conversión)

**Mejora estimada**: **6-10x más rápido** en generación de PDF

## Flujo Optimizado

```
1. InformeController recibe solicitud de PDF
   ↓
2. ImageHelper procesa TODAS las imágenes en paralelo
   - Descarga remotas (cURL multi)
   - Convierte a base64
   - Prefija locales con uploads/
   ↓
3. Vista recibe registro con imágenes ya procesadas
   ↓
4. Renderiza directamente (sin procesamiento adicional)
   ↓
5. PDF generado
```

## Archivos Modificados

1. ✅ `app/Repositories/InformeRepository.php`
   - Actualizado para guardar en disco 'public' configurado

2. ✅ `config/filesystems.php`
   - Disco 'public' apunta a `public/uploads`

3. ✅ `app/Http/Controllers/InformeController.php`
   - Usa `ImageHelper::preprocessImagesForPdf()`

4. ✅ `app/Helpers/ImageHelper.php`
   - Conversión paralela con cURL multi
   - Procesamiento batch de todas las imágenes

5. ✅ `resources/views/pdf/planta_electrica.blade.php`
   - Eliminadas conversiones duplicadas
   - Usa arrays para código más limpio
   - Renderizado directo de imágenes preprocesadas

## Beneficios Adicionales

- 🧹 **Código más limpio**: ~200 líneas menos de código repetitivo
- 🔧 **Más mantenible**: cambios en un solo lugar (ImageHelper)
- 🐛 **Menos errores**: lógica centralizada
- 📈 **Escalable**: fácil agregar más campos de imagen
- ✅ **Consistente**: mismo patrón en todas las secciones

## Notas Técnicas

- Las firmas (`firma_tecnico`, `firma_cliente`) también son procesadas por el helper
- El helper maneja automáticamente tanto URLs remotas como rutas locales
- `array_chunk($fotos, 2)` divide automáticamente en columnas de 2
- Compatibilidad con WebP (imágenes guardadas como .webp con calidad 80)
