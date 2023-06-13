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
        return $haircut_service_id;
        //search for all variations of haircut
        $haircut_variations = Variation::where('service_id', $haircut_service_id)->get();
        return response()->json([
            'message' => 'All haircuts',
            'data' => $haircut_variations
        ], 200);
    }
    public function search(Request $request)
    {
        //search for variations of haircut
        $searchTerm = $request->input('hairCutName');
        $haircuts_service_id = Service::whereRaw('LOWER(REPLACE(name, " ", "")) LIKE ?', ['%' . strtolower(str_replace(' ', '', 'haircut')) . '%'])
            ->first()->id;
        $haircuts = Variation::whereRaw('LOWER(REPLACE(name, " ", "")) LIKE ?', ['%' . strtolower(str_replace(' ', '', $searchTerm)) . '%'])
            ->where('service_id', $haircuts_service_id)
            ->get();
        return $haircuts;
    }
}