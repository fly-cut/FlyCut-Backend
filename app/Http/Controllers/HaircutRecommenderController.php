<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Variation;
use GuzzleHttp\Client;


class HaircutRecommenderController extends Controller
{
    public function recommendHaircut(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);

        $image = $request->file('image');
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images/'), $image_name);


        // $recommendedHaircuts = Http::withHeaders(['content-type' => 'application/json'])
        //     ->post(env('HAIRCUT_RECOMMENDER_URL'), [
        //         'image' => file_get_contents(public_path('images/' . $image_name)),
        //     ])->json();

        $client = new Client();
        $recommendedHaircuts = $client->request('POST', env('HAIRCUT_RECOMMENDER_URL'), [
            'multipart' => [
                [
                    'name' => 'image',
                    'contents' => fopen(public_path('images/'.$image_name), 'r'),
                ],
            ],
        ]);

        $haircuts = [];
        $recommendedHaircuts = json_decode($recommendedHaircuts->getBody()->getContents(), true);
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