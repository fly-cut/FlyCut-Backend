<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Slot;
use App\Repositories\SlotRepository;
use App\Exceptions\SlotException;
use Illuminate\Database\QueryException;
use App\Http\Requests\ChangeSlotStatusRequest;
use App\Http\Requests\ChangeSlotStatusToBusyRequest;
use App\Http\Requests\ChangeSlotStatusToFreeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SlotService
{
    private $slotRepository;

    public function __construct(SlotRepository $slotRepository)
    {
        $this->slotRepository = $slotRepository;
    }

    public function getById(int $id): Slot
    {
        try {
            return $this->slotRepository->getById($id);
        } catch (\Exception $e) {
            throw new SlotException('Failed to get the slot', 500, $e);
        }
    }

    public function getReservedSlotsByDayAndBarber(Request $request): Collection
    {
        $day = $request->input('day');
        $barberId = $request->input('barber_id');
        return $this->slotRepository->getReservedSlotsByDayAndBarber($day, $barberId);
    }

    public function changeSlotStatusToBusy(ChangeSlotStatusToBusyRequest $request): void
    {
        try {
            $start = Carbon::parse($request->input('start_time'));
            $end = Carbon::parse($request->input('end_time'));
            $barberId = $request->input('barber_id');

            // Check if the slot already exists
            $existingSlot = $this->slotRepository->getSlotByStartAndEnd($start, $end, $barberId);
            if ($existingSlot) {
                throw new SlotException('The slot already exists');
            }

            // Check if the slot overlaps with another slot
            $overlappingSlot = $this->slotRepository->getOverlappingSlot($start, $end, $barberId);
            if ($overlappingSlot) {
                throw new SlotException('The slot overlaps with another slot');
            }

            $slot = $this->slotRepository->create([
                'start_time' => $start,
                'end_time' => $end,
                'barber_id' => $barberId,
                'reservation_id' => null,
            ]);

            $slot->status = 'busy';
            $slot->save();
        } catch (QueryException $e) {
            throw new SlotException('Failed to change slot status', 500, $e);
        }
    }

    public function changeSlotStatusToFree(ChangeSlotStatusToFreeRequest $request)
    {
        try {
            $slotId = $request->input('slotId');
            $barberId = $request->input('barber_id');
            $slot = $this->slotRepository->getByIdAndBarber($slotId, $barberId);

            if ($slot->status === 'busy') {
                $this->slotRepository->delete($slot);
            } else {
                throw new SlotException('The slot is not busy');
            }
        } catch (\Exception $e) {
            throw new SlotException('Failed to change slot status to free', 500, $e);
        }
    }
}
