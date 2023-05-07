<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarberRating;
use App\Models\Barber;

class BarberRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'client_id' => 'required|exists:clients,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $barberRating = BarberRating::create($request->all());
        $barber = Barber::find($request->barber_id);
        $barber->rating = $barber->ratings()->avg('rating');
        $barber->rating_count = $barber->ratings()->count();
        $barber->save();

        return response()->json($barberRating, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $barberRating = BarberRating::find($id);
        $barberRating->update($request->all());
        $barber = Barber::find($barberRating->barber_id);
        $barber->rating = $barber->ratings()->avg('rating');
        $barber->rating_count = $barber->ratings()->count();
        $barber->save();

        return response()->json($barberRating, 200);
    }

    public function destroy($id)
    {
        $barberRating = BarberRating::find($id);
        $barberRating->delete();
        $barber = Barber::find($barberRating->barber_id);
        $barber->rating = $barber->ratings()->avg('rating');
        $barber->rating_count = $barber->ratings()->count();
        $barber->save();

        return response()->json(null, 204);
    }

    public function getRatings($id)
    {
        $barberRatings = BarberRating::where('barber_id', $id)->get();
        return response()->json($barberRatings, 200);
    }


}
