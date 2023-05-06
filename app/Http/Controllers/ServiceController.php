<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::all();

        return response()->json($services);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'slots' => 'required|numeric',
        ]);

        $service = Service::create($validatedData);

        return response()->json($service, 201);
    }

    public function show($id): JsonResponse
    {
        $service = Service::find($id);
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json($service);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $service = Service::find($id);
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'string',
            'price' => 'numeric',
            'slots' => 'numeric',
        ]);

        $service->update($validatedData);

        return response()->json($service);
    }

    public function destroy($id): JsonResponse
    {
        $service = Service::find($id);
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $service->delete();

        return response()->json(['message' => 'Service deleted']);
    }
}
