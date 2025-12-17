<?php

namespace App\Repositories;

use App\Interfaces\TableroElectricoInterface;
use App\Models\Solicitud;
use App\Models\TableroElectrico;
use Illuminate\Support\Facades\Storage;

class TableroElectricoRepository extends BaseRepository implements TableroElectricoInterface
{
    public function model()
    {
        return TableroElectrico::class;
    }

    public function create(array $data)
    {
        // Procesar fotos estado inicial
        $data = $this->handlePhotoUploads($data, 'inicial');

        // Procesar fotos durante
        $data = $this->handlePhotoUploads($data, 'durante');

        // Procesar fotos después
        $data = $this->handlePhotoUploads($data, 'despues');

        // Actualizar solicitud relacionada
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
        $tablero = $this->find($id);

        // Procesar fotos estado inicial
        $data = $this->handlePhotoUploads($data, 'inicial', $tablero);

        // Procesar fotos durante
        $data = $this->handlePhotoUploads($data, 'durante', $tablero);

        // Procesar fotos después
        $data = $this->handlePhotoUploads($data, 'despues', $tablero);

        return parent::update($id, $data);
    }

    /**
     * Maneja la subida de fotos para una sección específica
     *
     * @param  array  $data  Datos del formulario
     * @param  string  $section  Sección de fotos (inicial, durante, despues)
     * @param  TableroElectrico|null  $tablero  Tablero existente (para actualización)
     * @return array Datos actualizados con rutas de fotos
     */
    private function handlePhotoUploads(array $data, string $section, ?TableroElectrico $tablero = null): array
    {
        $photoFields = $this->getPhotoFields($section);

        foreach ($photoFields as $field) {
            if (isset($data[$field]) && $data[$field]) {
                // Si hay un archivo nuevo
                if (is_object($data[$field]) && method_exists($data[$field], 'store')) {
                    // Eliminar foto anterior si existe
                    if ($tablero && $tablero->$field) {
                        Storage::disk('public')->delete($tablero->$field);
                    }

                    // Guardar nueva foto
                    $data[$field] = $data[$field]->store("tableros/{$section}", 'public');
                }
            }
        }

        return $data;
    }

    /**
     * Obtiene los campos de fotos según la sección
     */
    private function getPhotoFields(string $section): array
    {
        $fields = [
            'inicial' => [
                'Foto_uno_antes',
                'Foto_dos_antes',
                'Foto_tres_antes',
            ],
            'durante' => [
                'foto_uno_durante',
                'foto_dos_durante',
                'foto_tres_durante',
                'foto_cuatro_durante',
                'foto_cinco_durante',
                'foto_seis_durante',
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
     * Elimina un informe de tablero eléctrico y sus archivos asociados
     *
     * @param  mixed  $id
     * @return bool
     */
    public function delete($id)
    {
        $tablero = $this->find($id);

        // Eliminar todas las fotos asociadas
        $allPhotoFields = array_merge(
            $this->getPhotoFields('inicial'),
            $this->getPhotoFields('durante'),
            $this->getPhotoFields('despues')
        );

        foreach ($allPhotoFields as $field) {
            if ($tablero->$field) {
                Storage::disk('public')->delete($tablero->$field);
            }
        }

        return parent::delete($id);
    }
}
