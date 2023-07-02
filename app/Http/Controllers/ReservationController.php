<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $this->validate($request, [
            'services' => 'required|array',
        ]);

        $reservationService = new ReservationService();

        return $reservationService->store($request);
    }

    public static function getStatus(Reservation $reservation)
    {
        $timeNow = Carbon::now('Africa/Cairo');
        $currentTime = Carbon::parse($timeNow)->subHours(12)->addHour();
        $slotCount = Slot::where('reservation_id', $reservation->id)->count();
        $reservationDateTime = Carbon::parse($reservation->date, 'Africa/Cairo')->subHours(12);
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
