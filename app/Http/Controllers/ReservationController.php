<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\Slot;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();
        return $this->reservationService->store($data);
    }

    public function getStatus(Reservation $reservation)
    {
        $this->reservationService->getstatus($reservation);
    }
}