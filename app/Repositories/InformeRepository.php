<?php

namespace App\Repositories;

use App\Interfaces\InformeInterface;
use App\Models\Informe;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Storage;

class InformeRepository extends BaseRepository implements InformeInterface
{
    public function model()
    {
        return Informe::class;
    }

    public function create(array $data)
    {
        // Procesar firmas (convertir base64 si es necesario o dejar como está)
        // Las firmas ya vienen en formato base64 desde el frontend (SignaturePad)

        // Procesar fotos antes
        $data = $this->handlePhotoUploads($data, 'antes');

        // Procesar fotos durante
        $data = $this->handlePhotoUploads($data, 'durante');

        // Procesar fotos después
        $data = $this->handlePhotoUploads($data, 'despues');

        $solicitudId = $data['solicitud_id'] ?? null;
        if ($solicitudId) {
            $solicitud = Solicitud::find($solicitudId);
            if ($solicitud) {
                $solicitud->informe_generado = true;
                if (isset($data['nombre_cliente'])) {
                    $solicitud->firma_cliente = $data['nombre_cliente'];
                }
                $solicitud->estado = 'Proceso';
                $solicitud->fecha_informe = now()->toDateTimeString();
                $solicitud->save();
            }
        }

        return parent::create($data);
    }

    public function update($id, array $data)
    {
        $informe = $this->find($id);

        // Procesar fotos antes
        $data = $this->handlePhotoUploads($data, 'antes', $informe);

        // Procesar fotos durante
        $data = $this->handlePhotoUploads($data, 'durante', $informe);

        // Procesar fotos después
        $data = $this->handlePhotoUploads($data, 'despues', $informe);

        return parent::update($id, $data);
    }

    /**
     * Maneja la subida de fotos para una sección específica
     *
     * @param  array  $data  Datos del formulario
     * @param  string  $section  Sección de fotos (antes, durante, despues)
     * @param  Informe|null  $informe  Informe existente (para actualización)
     * @return array Datos actualizados con rutas de fotos
     */
    private function handlePhotoUploads(array $data, string $section, ?Informe $informe = null): array
    {
        $photoFields = $this->getPhotoFields($section);

        foreach ($photoFields as $field) {
            if (isset($data[$field]) && $data[$field]) {
                // Si hay un archivo nuevo
                if (is_object($data[$field]) && method_exists($data[$field], 'store')) {
                    // Eliminar foto anterior si existe
                    if ($informe && $informe->$field) {
                        Storage::disk('public')->delete($informe->$field);
                    }

                    // Convertir y comprimir a WebP
                    $data[$field] = $this->convertToWebP($data[$field], $section);
                }
            }
        }

        return $data;
    }

    /**
     * Convierte una imagen a formato WebP y la comprime
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $section
     * @return string Ruta de la imagen guardada
     */
    private function convertToWebP($file, string $section): string
    {
        // Leer la imagen original
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        
        if ($image === false) {
            throw new \Exception('No se pudo procesar la imagen');
        }

        // Mantener transparencia si es PNG
        imagealphablending($image, false);
        imagesavealpha($image, true);

        // Generar nombre único para el archivo
        $filename = uniqid() . '.webp';
        $filePath = "informes/{$section}/{$filename}";

        // Crear el contenido WebP en un buffer de salida
        ob_start();
        imagewebp($image, null, 80); // null = output al buffer en lugar de archivo
        $webpContent = ob_get_clean();

        // Liberar memoria
        imagedestroy($image);

        // Guardar usando el disco 'public' configurado en filesystems.php
        Storage::disk('public')->put($filePath, $webpContent);

        // Retornar la ruta relativa
        return $filePath;
    }

    /**
     * Obtiene los campos de fotos según la sección
     */
    private function getPhotoFields(string $section): array
    {
        $fields = [
            'antes' => [
                'foto_uno_antes',
                'foto_dos_antes',
                'foto_tres_antes',
            ],
            'durante' => [
                'foto_uno_durante',
                'foto_dos_durante',
                'foto_tres_durante',
                'foto_cuatro_durante',
                'foto_cinco_durante',
                'foto_seis_durante',
                'foto_siete_durante',
                'foto_ocho_durante',
                'foto_nueve_durante',
            ],
            'despues' => [
                'foto_uno_despues',
                'foto_dos_despues',
                'foto_tres_despues',
            ],
        ];

        return $fields[$section] ?? [];
    }

    /**
     * Elimina un informe y sus archivos asociados
     *
     * @param  mixed  $id
     * @return bool
     */
    public function delete($id)
    {
        $informe = $this->find($id);

        // Eliminar todas las fotos asociadas
        $allPhotoFields = array_merge(
            $this->getPhotoFields('antes'),
            $this->getPhotoFields('durante'),
            $this->getPhotoFields('despues')
        );

        foreach ($allPhotoFields as $field) {
            if ($informe->$field) {
                Storage::disk('public')->delete($informe->$field);
            }
        }

        return parent::delete($id);
    }
}
