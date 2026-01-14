<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Optimiza una imagen: redimensiona, comprime y convierte a WebP
     * 
     * @param string $imageData Datos binarios de la imagen
     * @param int $maxWidth Ancho máximo (default: 1200px)
     * @param int $maxHeight Altura máxima (default: 1200px)
     * @param int $quality Calidad WebP (default: 75)
     * @return string|false Datos binarios de la imagen optimizada o false si falla
     */
    private static function optimizeImage(string $imageData, int $maxWidth = 1200, int $maxHeight = 1200, int $quality = 75)
    {
        try {
            // Crear imagen desde los datos
            $image = @imagecreatefromstring($imageData);
            
            if ($image === false) {
                return false;
            }

            // Obtener dimensiones originales
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calcular nuevas dimensiones manteniendo el aspect ratio
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            
            // Solo redimensionar si la imagen es más grande que el máximo
            if ($ratio < 1) {
                $newWidth = (int)round($originalWidth * $ratio);
                $newHeight = (int)round($originalHeight * $ratio);
                
                // Crear imagen redimensionada
                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                
                // Mantener transparencia para PNG
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                
                // Redimensionar con alta calidad
                imagecopyresampled(
                    $resizedImage, $image,
                    0, 0, 0, 0,
                    $newWidth, $newHeight,
                    $originalWidth, $originalHeight
                );
                
                imagedestroy($image);
                $image = $resizedImage;
            }

            // Convertir a WebP y obtener los datos
            ob_start();
            imagewebp($image, null, $quality);
            $webpData = ob_get_clean();
            
            imagedestroy($image);
            
            return $webpData;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Convierte múltiples URLs de imágenes a base64 en paralelo usando cURL multi
     * 
     * @param array $urls Array de URLs de imágenes
     * @return array Array con las imágenes convertidas a base64 (mismo orden que input)
     */
    public static function convertImagesToBase64Parallel(array $urls): array
    {
        if (empty($urls)) {
            return [];
        }

        // Filtrar URLs vacías
        $validUrls = array_filter($urls, fn($url) => !empty($url));
        
        if (empty($validUrls)) {
            return array_fill(0, count($urls), null);
        }

        // Crear un mapa para mantener el orden original
        $urlMap = [];
        foreach ($urls as $index => $url) {
            if (!empty($url)) {
                $urlMap[$index] = $url;
            }
        }

        // Inicializar cURL multi
        $multiHandle = curl_multi_init();
        $curlHandles = [];
        
        // Crear handles para cada URL
        foreach ($urlMap as $index => $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            
            curl_multi_add_handle($multiHandle, $ch);
            $curlHandles[$index] = $ch;
        }

        // Ejecutar todas las peticiones en paralelo
        $running = null;
        do {
            curl_multi_exec($multiHandle, $running);
            curl_multi_select($multiHandle);
        } while ($running > 0);

        // Recoger los resultados
        $results = array_fill(0, count($urls), null);
        
        foreach ($curlHandles as $index => $ch) {
            $imageData = curl_multi_getcontent($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if ($imageData !== false && $httpCode == 200) {
                // Optimizar la imagen antes de convertir a base64
                $optimizedImageData = self::optimizeImage($imageData);
                
                if ($optimizedImageData !== false) {
                    $base64 = base64_encode($optimizedImageData);
                    $results[$index] = 'data:image/webp;base64,' . $base64;
                }
            }
            
            curl_multi_remove_handle($multiHandle, $ch);
            curl_close($ch);
        }
        
        curl_multi_close($multiHandle);
        
        return $results;
    }

    /**
     * Preprocesa todas las imágenes de un registro para PDF
     * 
     * @param object $registro Registro con URLs de imágenes
     * @return object Registro con imágenes convertidas
     */
    public static function preprocessImagesForPdf($registro): object
    {
        // Lista de campos de imagen a procesar (PlantaElectrica y TableroElectrico)
        $imageFields = [
            // PlantaElectrica (minúsculas)
            'foto_uno_antes',
            'foto_dos_antes',
            'foto_tres_antes',
            'foto_uno_durante',
            'foto_dos_durante',
            'foto_tres_durante',
            'foto_cuatro_durante',
            'foto_cinco_durante',
            'foto_seis_durante',
            'foto_siete_durante',
            'foto_ocho_durante',
            'foto_nueve_durante',
            'foto_uno_despues',
            'foto_dos_despues',
            'foto_tres_despues',
            // TableroElectrico (con mayúsculas)
            'Foto_uno_antes',
            'Foto_dos_antes',
            'Foto_tres_antes',
            // Firmas (ambos modelos)
            'firma_tecnico',
            'firma_cliente',
        ];

        // Clasificar imágenes en una sola pasada
        $urlsToConvert = [];
        $fieldMapping = [];
        $localFields = [];
        
        foreach ($imageFields as $field) {
            if (empty($registro->$field)) {
                continue; // Saltar campos vacíos
            }
            
            $url = $registro->$field;
            
            // URLs remotas: necesitan descarga y optimización
            if (str_contains($url, 'https://reporting.genservices.com.co/storage/')) {
                $urlsToConvert[] = $url;
                $fieldMapping[] = $field;
            }
            // Data URLs (firmas): ya están procesadas, no tocar
            elseif (str_contains($url, 'data:')) {
                // Ya está en base64, no hacer nada
                continue;
            }
            // Imágenes locales: solo agregar prefijo
            elseif (!str_contains($url, 'http')) {
                $localFields[] = $field;
            }
        }

        // Convertir imágenes remotas en paralelo (solo si hay)
        if (!empty($urlsToConvert)) {
            $convertedImages = self::convertImagesToBase64Parallel($urlsToConvert);
            
            foreach ($fieldMapping as $index => $field) {
                if ($convertedImages[$index] !== null) {
                    $registro->$field = $convertedImages[$index];
                }
            }
        }

        // Procesar imágenes locales (muy rápido, solo agregar prefijo)
        foreach ($localFields as $field) {
            $registro->$field = 'uploads/' . $registro->$field;
        }

        return $registro;
    }
}
