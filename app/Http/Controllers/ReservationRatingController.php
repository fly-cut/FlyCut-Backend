<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRatingRequest;
use App\Http\Requests\UpdateReservationRatingRequest;
use App\Models\Barber;
use App\Models\Barbershop;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\ReservationRating;
use App\Models\Service;
use App\Services\ReservationRatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationRatingController extends Controller
{

    protected $reservationRatingService;

    public function __construct(ReservationRatingService $reservationRatingService)
    {
        $this->reservationRatingService = $reservationRatingService;
    }

    public function store(StoreReservationRatingRequest $request)
    {
        $reservationRating = $this->reservationRatingService->create($request);

        return response()->json($reservationRating, 201);
    }

    public function update(UpdateReservationRatingRequest $request, $id)
    {
        $reservationRating = $this->reservationRatingService->update($request, $id);

        return response()->json($reservationRating, 200);
    }

    public function destroy($id)
    {
        $reservationRating = $this->reservationRatingService->delete($id);
        if (!$reservationRating) {
            return response()->json(['message' => 'Rating not found.'], 404);
        }

        $this->reservationRatingService->getBarberRatingByReservationId($id);

        return response()->json(['message' => 'Rating deleted successfully.'], 200);
    }

    public function getBarberRatings($id)
    {
        $barber = Barber::find($id);
        if (!$barber) {
            return response()->json(['message' => 'Barber not found.'], 404);
        }
        $barberRatings = $this->reservationRatingService->getBarberRatings($id);

        return response()->json($barberRatings, 200);
    }

    public function getBarbershopRatings($id)
    {
        $barbershop = Barbershop::find($id);
        if (!$barbershop) {
            return response()->json(['message' => 'Barbershop not found.'], 404);
        }

        $barbershopRatings = $this->reservationRatingService->getBarbershopRatings($id);

        return response()->json($barbershopRatings, 200);
    }

    public function getRatingByReservationId($id)
    {
        $rating = $this->reservationRatingService->getBarberRatingByReservationId($id);
        if (!$rating) {
            return response()->json(['message' => 'Rating not found.'], 404);
        }

        $rating = $this->reservationRatingService->getBarberRatingByReservationId($id);

        return response()->json($rating, 200);
    }
}
