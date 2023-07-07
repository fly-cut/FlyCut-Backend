<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Variation;

class HaircutRecommenderController extends Controller
{
    public function recommendHaircut(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);

        $recommendedHaircuts = Http::withHeaders(['content-type' => 'application/json'])
            ->post(env('HAIRCUT_RECOMMENDER_URL'), [
                'image' => $request->image,
            ])->json();

        $haircuts = [];

        foreach ($recommendedHaircuts as $recommendedHaircut) {
            $haircut = Variation::where('name', 'like', '%' . $recommendedHaircut . '%')->get();

            if ($haircut->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No haircut found',
                ]);
            }
            $haircuts[] = $haircut;
        }

        return response()->json([
            'success' => true,
            'haircuts' => $haircuts,
        ], 200);
    }
}
