<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }
    public function index(): JsonResponse
    {
        $services = $this->serviceService->getAllServices();

        return response()->json($services, 200);
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $service = $this->serviceService->createService($request);

        return response()->json($service, 201);
    }

    public function show($id): JsonResponse
    {
        $service = $this->serviceService->getServiceById($id);
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json($service, 200);
    }

    public function update(UpdateServiceRequest $request, $id): JsonResponse
    {
        $service = $this->updateService($request, $id);
        return response()->json($service, 200);
    }
    public function destroy($id): JsonResponse
    {
        $service = $this->serviceService->findServiceById($id);
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $this->serviceService->deleteService($id);
        return response()->json(['message' => 'Service deleted'], 200);
    }

    /**
     * Get variations of a particular service
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function getServiceVariations($service_id)
    {
        $service = $this->serviceService->getServiceById($service_id);
        if (! $service) {
            return response()->json(['error' => 'There is no such service exists!'], 404);
        }
        $variations = $service->variations;

        return response()->json(['variations' => $variations], 200);
    }
}
