<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Service;
use App\Models\Variation;
use App\Models\Barbershop;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Services\BarberService;
use App\Services\ReservationServiceAttachment;
use App\Repositories\SlotRepository;
use App\Services\NotificationService;
use App\Repositories\ClientRepository;
use App\Http\Controllers\BarberController;
use App\Repositories\ReservationRepository;
use App\Services\ReservationSlotAttachment;
use App\Services\ReservationCreationService;
use App\Services\BarberAvailabilityService;

class ReservationService
{
    private $reservationCreationService;
    private $reservationServiceAttachment;
    private $reservationSlotAttachment;
    private $notificationService;
    private $barberAvailability;
    public function __construct(
        ReservationCreationService $reservationCreationService,
        ReservationServiceAttachment $reservationServiceAttachment,
        ReservationSlotAttachment $reservationSlotAttachment,
        BarberAvailabilityService $barberAvailability,
        NotificationService $notificationService,
    ) {
        $this->reservationCreationService = $reservationCreationService;
        $this->reservationServiceAttachment = $reservationServiceAttachment;
        $this->reservationSlotAttachment = $reservationSlotAttachment;
        $this->notificationService = $notificationService;
        $this->barberAvailability = $barberAvailability;
    }

    public function store($request)
    {
        $availability = $this->checkBarberAvailability($request);

        if (!$availability) {
            return response()->json(['message' => 'No slots available'], 422);
        }

        try {
            $reservation = $this->reservationCreationService->createReservation($request);
            $this->notificationService->sendNotification($reservation);
            $this->reservationServiceAttachment->attachServicesToReservation($request, $reservation);
            $this->reservationSlotAttachment->attachSlotsToReservation($request, $reservation);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Reservation created', 'reservation' => $reservation]);
    }

    private function checkBarberAvailability($request)
    {
        $startTime = $request['start_time'];
        $services = $request['services'];
        $totalslots = $this->calculateTotalSlots($request, $services);
        $barberId = $request['barber_id'];
        $response = $this->barberAvailability->checkAvailability($startTime, $totalslots, $barberId);

        return $response;
    }

    private function calculateTotalSlots($request, array $services)
    {
        $totalslots = 0;

        foreach ($services as $service) {
            $currentService = Service::where('name', $service['name'])->first();
            $id = $currentService->id;
            $barbershop = Barbershop::where('id', $request['barbershop_id'])->first();

            foreach ($barbershop->services as $service) {
                if ($service->id == $id) {
                    $totalslots += $service->pivot->slots;
                }
            }
        }

        return $totalslots;
    }
}
