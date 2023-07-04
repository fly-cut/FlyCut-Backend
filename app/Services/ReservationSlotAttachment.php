<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Service;
use App\Models\Barbershop;
use App\Models\Reservation;
use App\Repositories\SlotRepository;

class ReservationSlotAttachment
{
    private $slotrepository;

    public function __construct(SlotRepository $slotrepository)
    {
        $this->slotrepository = $slotrepository;
    }

    public function attachSlotsToReservation($request, Reservation $reservation)
    {
        $services = $request['services'];
        $startTime = $request['start_time'];
        $start = Carbon::parse($startTime);
        $totalPrice = 0;

        foreach ($services as $service) {
            $currentService = Service::where('name', $service['name'])->first();
            $id = $currentService->id;
            $barbershop = Barbershop::where('id', $request['barbershop_id'])->first();
            $numberOfSlots = $this->getNumberOfSlots($barbershop, $id);
            $totalPrice += $this->getTotalPrice($barbershop, $id);

            for ($i = 0; $i < $numberOfSlots; $i++) {
                $intervalEnd = $start->copy()->addMinutes(15);
                $slotData = [
                    'start_time' => $start->format('Y-m-d H:i:s'),
                    'end_time' => $intervalEnd->format('Y-m-d H:i:s'),
                    'barber_id' => $request['barber_id'],
                    'reservation_id' => $reservation->id,
                    'status' => 'reserved',
                ];

                $this->slotrepository->create($slotData);
                $start = $intervalEnd;
            }
        }

        $reservation->price = $totalPrice;
        $reservation->save();
    }

    private function getNumberOfSlots(Barbershop $barbershop, $id)
    {
        $numberOfSlots = 0;

        foreach ($barbershop->services as $service) {
            if ($service->id == $id) {
                $numberOfSlots = $service->pivot->slots;
                break;
            }
        }

        return $numberOfSlots;
    }

    private function getTotalPrice(Barbershop $barbershop, $id)
    {
        $totalPrice = 0;

        foreach ($barbershop->services as $service) {
            if ($service->id == $id) {
                $totalPrice += $service->pivot->price;
                break;
            }
        }

        return $totalPrice;
    }
}
