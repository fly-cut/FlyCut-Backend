<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Variation;
use Illuminate\Http\Request;

class HairCutController extends Controller
{
    public function getAllHaircuts()
    {
        $searchTerm = 'haircut';
        $haircuts_service = Service::whereRaw('LOWER(REPLACE(name, " ", "")) LIKE ?', ['%' . strtolower(str_replace(' ', '', $searchTerm)) . '%'])
            ->first();
        $haircut_service_id = $haircuts_service->id;
        //search for all variations of haircut
        $haircut_variations = Variation::where('service_id', $haircut_service_id)->get();
        return response()->json([
            'message' => 'All haircuts',
            'data' => $haircut_variations
        ], 200);
    }
}