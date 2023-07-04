<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetReservedSlotsRequest;
use App\Http\Requests\ChangeSlotStatusRequest;
use App\Http\Requests\ChangeSlotStatusToBusyRequest;
use App\Http\Requests\ChangeSlotStatusToFreeRequest;
use App\Services\SlotService;
use Illuminate\Http\JsonResponse;

class SlotController extends Controller
{
    private $slotService;

    public function __construct(SlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    public function index()
    {
    }

    public function show($id)
    {
        $slot = $this->slotService->getById($id);
        return response($slot, 201);
    }

    public function getReservedSlots(GetReservedSlotsRequest $request)
    {
        $slots = $this->slotService->getReservedSlotsByDayAndBarber($request);
        return response($slots, 201);
    }

    public function changeStatusToBusy(ChangeSlotStatusToBusyRequest $request)
    {
        $this->slotService->changeSlotStatusToBusy($request);
        $message = 'Slot status changed to busy';
        return response($message, 201);
    }

    public function changeStatusToFree(ChangeSlotStatusToFreeRequest $request)
    {
        $this->slotService->changeSlotStatusToFree($request);
        $message = 'Slot status changed to free';
        return response()->json(['message' => $message]);
    }
}
