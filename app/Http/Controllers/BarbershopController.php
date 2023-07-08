<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarbershopOwner;
use App\Services\BarbershopService;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBarbershopRequest;
use App\Http\Requests\UpdateBarbershopRequest;
use App\Http\Requests\GetNearbyBarbershopsRequest;
use App\Http\Requests\AddServicesToBarbershopRequest;
use App\Http\Requests\EditServicePriceAndSlotsRequest;
use App\Http\Requests\RemoveServicesFromBarbershopRequest;


class BarbershopController extends Controller
{
    protected $barbershopService;

    public function __construct(BarbershopService $barbershopService)
    {
        $this->barbershopService = $barbershopService;
    }
    public function indexBarbershop()
    {
        $barbershops = $this->barbershopService->getAllBarbershops();
        return response()->json([
            'status' => 200,
            'barbershops' => $barbershops,
        ], 200);
    }
    public function addBarbershop(StoreBarbershopRequest $request)
    {
        $barbershop = $this->barbershopService->createBarbershop($request);
        $barbershop_owner = BarbershopOwner::find(Auth::id());
        return response()->json([
            'status' => 200,
            'message' => 'Barbershop has been added successfully',
            'barbershop' => $barbershop,
            'barbershop_owner' => $barbershop_owner,
        ], 200);
    }


    public function showBarbershop($barbershop_id)
    {
        $barbershop = $this->barbershopService->getBarbershopByOwnerId($barbershop_id);
        if (is_null($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be shown!',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'barbershop' => $barbershop,
        ], 200);
    }

    public function updateBarbershop(UpdateBarbershopRequest $request, $barbershop_id)
    {
        $barbershop = $this->barbershopService->getBarbershop($barbershop_id);
        if (!$barbershop || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be updated!',
            ], 404);
        }
        $barbershop = $this->barbershopService->updateBarbershop($barbershop_id, $request);
        return response()->json([
            'status' => 200,
            'message' => 'Barbershop updated successfully',
            'barbershop' => $barbershop,
        ], 200);
    }


    public function destroyBarbershop($barbershop_id)
    {
        $barbershop = $this->barbershopService->getBarbershopByOwnerId($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be deleted!',
            ], 404);
        } else {
            $this->barbershopService->deleteBarbershop($barbershop_id);

            return response()->json([
                'status' => 200,
                'message' => 'The barbershop has been deleted!',
            ], 200);
        }
    }

    public function addServicesToBarbershop(AddServicesToBarbershopRequest $request)
    {
        $this->barbershopService->addServicesToBarbershop($request);
        return response()->json([
            'status' => 200,
            'message' => 'Services added successfully to the barbershop',
        ], 200);
    }

    public function removeServicesFromBarbershop(RemoveServicesFromBarbershopRequest $request)
    {
        $this->barbershopService->removeServicesFromBarbershop($request);
        return response()->json([
            'status' => 200,
            'message' => 'Services removed successfully from the barbershop',
        ], 200);
    }

    public function editServicePriceAndSlots(EditServicePriceAndSlotsRequest $request)
    {
        $this->barbershopService->editServicePriceAndSlots($request);
        return response()->json([
            'status' => 200,
            'message' => 'Service prices and slots updated successfully',
        ], 200);
    }


    public function getBarbershopServicesWithPriceAndSlots($barbershop_id)
    {
        $barbershop = $this->barbershopService->getBarbershopByOwnerId($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to get its services!',
            ], 404);
        }
        $services = $this->barbershopService->getBarbershopServices($barbershop_id);

        return response()->json([
            'status' => 200,
            'services' => $services,
        ], 200);
    }

    public function getBarbersOfBarbershop($barbershop_id)
    {
        $barbershop = $this->barbershopService->getBarbershop($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to get its barbers!',
            ], 404);
        }
        $barbers = $this->barbershopService->getBarbers($barbershop_id);

        return response()->json([
            'status' => 200,
            'barbers' => $barbers,
        ], 200);
    }

    public function search(SearchRequest $request)
    {
        $barbershops = $this->barbershopService->search($request);
        if (count($barbershops) > 0) {
            return response()->json([
                'status' => 200,
                'barbershops' => $barbershops,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found!',
            ], 404);
        }
    }

    public function getNearbyBarbershops(GetNearbyBarbershopsRequest $request)
    {
        $barbershops = $this->barbershopService->getNearbyBarbershops($request);
        if (count($barbershops) > 0) {
            return response()->json([
                'status' => 200,
                'barbershops' => $barbershops,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found!',
            ], 404);
        }
    }

    public function getReservations(Request $request)
    {
        $reservations = $this->barbershopService->getReservations($request);
        return response()->json($reservations, 200);
    }
}
