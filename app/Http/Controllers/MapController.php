<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Obtener todos los viajes activos para el mapa
     */
    public function getActiveTrips(): JsonResponse
    {
        $trips = Trip::where('status', 'active')
            ->where('available_seats', '>', 0)
            ->with('driver')
            ->get()
            ->map(function ($trip) {
                return [
                    'id' => $trip->id,
                    'origin_zone' => $trip->origin_zone,
                    'destination_zone' => $trip->destination_zone,
                    'departure_time' => $trip->departure_time->format('Y-m-d H:i'),
                    'available_seats' => $trip->available_seats,
                    'total_seats' => $trip->total_seats,
                    'driver_name' => $trip->driver->name,
                    'driver_avatar' => $trip->driver->avatar ? asset('storage/' . $trip->driver->avatar) : null,
                    'notes' => $trip->notes,
                ];
            });

        return response()->json($trips);
    }

    /**
     * Obtener coordenadas usando Nominatim (GRATIS)
     */
    public function geocode(Request $request): JsonResponse
    {
        $zone = $request->get('zone');

        if (!$zone) {
            return response()->json(['error' => 'Zone is required'], 400);
        }

        $url = "https://nominatim.openstreetmap.org/search";
        $params = [
            'q' => $zone . ', Ambato, Ecuador',
            'format' => 'json',
            'limit' => 1,
        ];

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: MoveShift-App/1.0',
            ]
        ]);

        $response = @file_get_contents($url . '?' . http_build_query($params), false, $context);
        
        if ($response === false) {
            return response()->json(['error' => 'Could not reach geocoding service'], 503);
        }

        $data = json_decode($response, true);

        if (!empty($data) && isset($data[0])) {
            $location = $data[0];
            return response()->json([
                'success' => true,
                'lat' => (float)$location['lat'],
                'lng' => (float)$location['lon'],
                'formatted_address' => $location['display_name'] ?? 'Location found',
            ]);
        }

        return response()->json(['error' => 'Location not found'], 404);
    }

    /**
     * Obtener ruta usando OSRM (GRATIS)
     */
    public function getDirections(Request $request): JsonResponse
    {
        $origin = $request->get('origin');
        $destination = $request->get('destination');

        if (!$origin || !$destination) {
            return response()->json(['error' => 'Origin and destination are required'], 400);
        }

        // Geocodificar origen
        $originGeoUrl = "https://nominatim.openstreetmap.org/search";
        $originParams = [
            'q' => $origin . ', Ambato, Ecuador',
            'format' => 'json',
            'limit' => 1,
        ];

        $context = stream_context_create([
            'http' => ['header' => 'User-Agent: MoveShift-App/1.0']
        ]);

        $originResponse = @file_get_contents($originGeoUrl . '?' . http_build_query($originParams), false, $context);
        $originData = json_decode($originResponse, true);

        if (empty($originData)) {
            return response()->json(['error' => 'Origin location not found'], 404);
        }

        // Geocodificar destino
        $destGeoUrl = "https://nominatim.openstreetmap.org/search";
        $destParams = [
            'q' => $destination . ', Ambato, Ecuador',
            'format' => 'json',
            'limit' => 1,
        ];

        $destResponse = @file_get_contents($destGeoUrl . '?' . http_build_query($destParams), false, $context);
        $destData = json_decode($destResponse, true);

        if (empty($destData)) {
            return response()->json(['error' => 'Destination location not found'], 404);
        }

        $originLat = $originData[0]['lat'];
        $originLng = $originData[0]['lon'];
        $destLat = $destData[0]['lat'];
        $destLng = $destData[0]['lon'];

        // Obtener ruta con OSRM
        $osrmUrl = "https://router.project-osrm.org/route/v1/driving/{$originLng},{$originLat};{$destLng},{$destLat}";
        $osrmParams = [
            'overview' => 'full',
            'steps' => 'true',
            'geometries' => 'geojson',
        ];

        $osrmResponse = @file_get_contents($osrmUrl . '?' . http_build_query($osrmParams), false, $context);
        
        if ($osrmResponse === false) {
            return response()->json(['error' => 'Could not calculate route'], 503);
        }

        $osrmData = json_decode($osrmResponse, true);

        if ($osrmData['code'] === 'Ok') {
            $route = $osrmData['routes'][0];
            $distance = $route['distance'] / 1000; 
            $duration = $route['duration'] / 60;

            return response()->json([
                'success' => true,
                'distance' => number_format($distance, 2) . ' km',
                'duration' => number_format($duration, 0) . ' min',
                'geometry' => $route['geometry'],
                'coordinates' => [
                    'origin' => [$originLat, $originLng],
                    'destination' => [$destLat, $destLng],
                ],
            ]);
        }

        return response()->json(['error' => 'Could not calculate route'], 404);
    }
}