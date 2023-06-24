<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Slot;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'services' => 'required|array',
        ]);

        $reservationService = new ReservationService();
        $reservation = $reservationService->store($request);

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation,
        ], 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
    public static function getStatus(Reservation $reservation)
    {
        $timeNow = Carbon::now('Africa/Cairo');
        $currentTime = Carbon::parse($timeNow)->subHours(12)->addHour();
        $slotCount = Slot::where('reservation_id', $reservation->id)->count();
        $reservationDateTime = Carbon::parse($reservation->date, 'Africa/Cairo');
        $reservationEndTime = $reservationDateTime->copy()->addMinutes(15 * $slotCount);

        if ($currentTime < $reservationDateTime) {
            $reservation->status = 'upcoming';
            $reservation->save();
        } elseif ($currentTime >= $reservationDateTime && $currentTime <= $reservationEndTime) {
            $reservation->status = 'in-progress';
            $reservation->save();
        } else {
            $reservation->status = 'completed';
            $reservation->save();
        }
    }
}
