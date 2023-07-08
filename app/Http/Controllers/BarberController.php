<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\SlotsNotAvailableException;
use App\Http\Requests\CreateBarberRequest;
use App\Http\Requests\StoreBarberRequest;
use App\Http\Requests\UpdateBarberRequest;
use App\Services\BarberService;
use Illuminate\Support\Facades\Response;

class BarberController extends Controller
{
    protected $barberService;

    public function __construct(BarberService $barberService)
    {
        $this->barberService = $barberService;
    }

    public function store(StoreBarberRequest $request)
    {
        $data = $request->validated();

        $barber = $this->barberService->store($data);

        $message = [
            'message' => 'Barber created successfully',
            'barber' => $barber,
        ];

        return response($message, 201);
    }

    public function update(UpdateBarberRequest $request, $barberId)
    {
        $data = $request->validated();

        $barber = $this->barberService->update($data, $barberId);

        return response()->json(['message' => 'Barber updated successfully'], 200);
    }

    public function destroy($barberId)
    {
        $this->barberService->destroy($barberId);
        return response()->json([
            'status' => 200,
            'message' => 'The barber has been deleted!',
        ], 200);
    }

    public function checkAvailability($startTime, $numberOfSlots, $barberId)
    {
        if ($this->barberService->checkAvailability($startTime, $numberOfSlots, $barberId)) {
            $message = 'Slots available';
        } else {
            $message = 'No slots available';
        }
        return response($message, 200);
    }
}
