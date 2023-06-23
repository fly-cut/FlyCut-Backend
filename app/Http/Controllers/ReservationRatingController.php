<?php

namespace App\Http\Controllers;

use App\Models\ReservationRating;
use App\Models\Barber;
use App\Models\Barbershop;
use Illuminate\Http\Request;

class ReservationRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barbershop_id' => 'required|exists:barbershops,id',
            'barber_id' => 'required|exists:barbers,id',
            'client_id' => 'required|exists:clients,id',
            'reservation_id' => 'required|exists:reservations,id',
            'barber_rating' => 'required|integer|between:1,5',
            'barbershop_rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $reservationRating = ReservationRating::create($request->all());
        $barber = Barber::find($request->barber_id);
        $barber->rating = ReservationRating::where('barber_id', $request->barber_id)->avg('barber_rating');
        $barber->rating_count = ReservationRating::where('barber_id', $request->barber_id)->count();
        $barber->save();

        $barbershop = Barbershop::find($request->barbershop_id);
        $barbershop->rating = ReservationRating::where('barbershop_id', $request->barbershop_id)->avg('barbershop_rating');
        $barbershop->rating_count = ReservationRating::where('barbershop_id', $request->barbershop_id)->count();
        $barbershop->save();

        return response()->json($reservationRating, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barber_rating' => 'required|integer|min:1|max:5',
            'barbershop_rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $reservationRating = ReservationRating::find($id);
        $reservationRating->update($request->all());
        
        $barber = Barber::find($reservationRating->barber_id);
        $barber->rating = ReservationRating::where('barber_id', $reservationRating->barber_id)->avg('barber_rating');
        $barber->rating_count = ReservationRating::where('barber_id', $reservationRating->barber_id)->count();
        $barber->save();

        $barbershop = Barbershop::find($reservationRating->barbershop_id);
        $barbershop->rating = ReservationRating::where('barbershop_id', $reservationRating->barbershop_id)->avg('barbershop_rating');
        $barbershop->rating_count = ReservationRating::where('barbershop_id', $reservationRating->barbershop_id)->count();
        $barbershop->save();

        return response()->json($reservationRating, 200);
    }

    public function destroy($id)
    {
        $reservationRating = ReservationRating::find($id);
        if (! $reservationRating) {
            return response()->json(['message' => 'Rating not found.'], 404);
        }
        
        $barber = Barber::find($reservationRating->barber_id);
        $barbershop = Barbershop::find($reservationRating->barbershop_id);
        $reservationRating->delete();
        if (ReservationRating::where('barbershop_id', $barbershop->barbershop_id)->count() > 0) {
            $barbershop->rating = ReservationRating::where('barbershop_id', $barbershop->barbershop_id)->avg('barbershop_rating');
        } else {
            $barbershop->rating = 5.0;
        }

        if (ReservationRating::where('barber_id', $barber->barber_id)->count() > 0) {
            $barber->rating = ReservationRating::where('barber_id', $barber->barber_id)->avg('barber_rating');
        } else {
            $barber->rating = 5.0;
        }
        $barbershop->rating_count = ReservationRating::where('barbershop_id', $barbershop->barbershop_id)->count();
        $barber->rating_count = ReservationRating::where('barber_id', $barber->barber_id)->count();
        $barbershop->save();
        $barber->save();
    
        return response()->json(['message' => 'Rating deleted successfully.'], 200);
    }
    public function getBarberRatings($id)
    {
        $barber = Barber::find($id);
        if (! $barber) {
            return response()->json(['message' => 'Barber not found.'], 404);
        }
        $barberRatings = ReservationRating::where('barber_id', $id)->get();

        return response()->json($barberRatings, 200);
    }

    public function getBarbershopRatings($id)
    {
        $barbershop = Barbershop::find($id);
        if (! $barbershop) {
            return response()->json(['message' => 'Barbershop not found.'], 404);
        }
        $barbershopRatings = ReservationRating::where('barbershop_id', $id)->get();

        return response()->json($barbershopRatings, 200);
    }
}
