<?php

namespace App\Helpers;

class ImageHelper
{
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
                // Detectar el tipo MIME de la imagen
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageData);
                
                // Si no se puede detectar, asumir JPEG
                if (!$mimeType || !str_starts_with($mimeType, 'image/')) {
                    $mimeType = 'image/jpeg';
                }
                
                $base64 = base64_encode($imageData);
                $results[$index] = 'data:' . $mimeType . ';base64,' . $base64;
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
        // Lista de campos de imagen a procesar
        $imageFields = [
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
            'firma_tecnico',
            'firma_cliente',
        ];

        // Recopilar todas las URLs que necesitan conversión
        $urlsToConvert = [];
        $fieldMapping = [];
        
        foreach ($imageFields as $field) {
            if (!empty($registro->$field)) {
                $url = $registro->$field;
                
                // Solo procesar URLs remotas
                if (str_contains($url, 'https://reporting.genservices.com.co/storage/')) {
                    $urlsToConvert[] = $url;
                    $fieldMapping[] = $field;
                }
            }
        }

        // Convertir todas las imágenes en paralelo
        if (!empty($urlsToConvert)) {
            $convertedImages = self::convertImagesToBase64Parallel($urlsToConvert);
            
            // Asignar las imágenes convertidas de vuelta al registro
            foreach ($fieldMapping as $index => $field) {
                if ($convertedImages[$index] !== null) {
                    $registro->$field = $convertedImages[$index];
                }
            }
        }

        // Procesar imágenes locales (no remotas)
        foreach ($imageFields as $field) {
            if (!empty($registro->$field)) {
                $url = $registro->$field;
                
                // Si no es una URL remota y no es un data URL, agregar el prefijo
                if (!str_contains($url, 'http') && !str_contains($url, 'data:')) {
                    $registro->$field = 'uploads/' . $url;
                }
            }
        }

        return $registro;
    }
}
