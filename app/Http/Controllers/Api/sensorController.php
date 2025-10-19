<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SensorController extends Controller
{
    public function index()
    {
        return Sensor::latest()->get();
    }

public function store(Request $request)
{
    try {
        $data = $request->validate([
            'device_id'   => ['required','string','max:100'],
            'temperature' => ['required','numeric'],
            'humidity'    => ['required','numeric'],
            'measured_at' => ['nullable','date'],
        ]);

        $sensor = Sensor::create($data);

        return response()->json([
            'message' => 'Sensor creado',
            'data' => $sensor
        ], 201);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Error al crear el sensor',
            'error' => app()->hasDebugModeEnabled() ? $e->getMessage() : 'Server error'
        ], 500);
    }
}




    public function show(Sensor $sensor) // Route Model Binding
    {
        return $sensor;
    }

    public function update(Request $request, Sensor $sensor)
    {
        $data = $request->validate([
            'device_id'   => ['sometimes','string','max:100'],
            'temperature' => ['sometimes','numeric'],
            'humidity'    => ['sometimes','numeric'],
            'measured_at' => ['sometimes','date'],
        ]);

        $sensor->update($data);

        return response()->json($sensor, 200);
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return response()->json(null, 204);
    }
}
