<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Models\BarbershopRating;
use Illuminate\Http\Request;

class BarbershopRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'barbershop_id' => 'required|exists:barbershops,id',
            'client_id' => 'required|exists:clients,id',
            'reservation_id' => 'required|exists:reservations,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $barbershopRating = BarbershopRating::create($request->all());
        $barbershop = Barbershop::find($request->barbershop_id);
        $barbershop->rating = $barbershop->ratings()->avg('rating');
        $barbershop->rating_count = $barbershop->ratings()->count();
        $barbershop->save();

        return response()->json($barbershopRating, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $barbershopRating = BarbershopRating::find($id);
        $barbershopRating->update($request->all());
        $barbershop = Barbershop::find($barbershopRating->barbershop_id);
        $barbershop->rating = $barbershop->ratings()->avg('rating');
        $barbershop->rating_count = $barbershop->ratings()->count();
        $barbershop->save();

        return response()->json($barbershopRating, 200);
    }

    public function destroy($id)
    {
        $barbershopRating = BarbershopRating::find($id);
        $barbershopRating->delete();
        $barbershop = Barbershop::find($barbershopRating->barbershop_id);
        if ($barbershop->ratings()->avg('rating')) {
            $barbershop->rating = $barbershop->ratings()->avg('rating');
        } else {
            $barbershop->rating = 5.0;
        }
        $barbershop->rating_count = $barbershop->ratings()->count();
        $barbershop->save();

        return response()->json(['message' => 'Rating deleted successfully'], 200);
    }

    public function getRatings($id)
    {
        $barbershop = Barbershop::find($id);
        if (! $barbershop) {
            return response()->json(['message' => 'Barbershop not found.'], 404);
        }
        $ratings = BarbershopRating::where('barbershop_id', $id)->get();

        return response()->json($ratings, 200);
    }
}
