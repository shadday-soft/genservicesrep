<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodingController extends Controller
{
    /**
     * Buscar ubicaciones por texto (geocodificación)
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
            'viewbox' => 'nullable|string',
        ]);

        try {
            $params = [
                'format' => 'json',
                'q' => $request->input('query'),
                'limit' => 10,
                'countrycodes' => 'co',
            ];

            // Si se proporciona un viewbox dinámico, usarlo
            if ($request->has('viewbox') && ! empty($request->input('viewbox'))) {
                $params['viewbox'] = $request->input('viewbox');
                $params['bounded'] = 1;
            }

            $response = Http::withHeaders([
                'User-Agent' => 'GenServicesApp/1.0',
            ])->get('https://nominatim.openstreetmap.org/search', $params);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al buscar ubicaciones'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error de conexión con el servicio de mapas'], 500);
        }
    }

    /**
     * Obtener información de ubicación por coordenadas (geocodificación inversa)
     */
    public function reverse(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'GenServicesApp/1.0',
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $request->lat,
                'lon' => $request->lng,
                'zoom' => 18,
                'addressdetails' => 1,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Error al obtener información de ubicación'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error de conexión con el servicio de mapas'], 500);
        }
    }
}
