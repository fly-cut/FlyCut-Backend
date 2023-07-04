<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Variation;
use Illuminate\Http\Request;

class HairCutController extends Controller
{
    public function getAllHaircuts()
    {
        $service = Service::where('name', 'haircut')->get()->first();
        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => 'No service found',
            ]);
        }
        $haircuts = Variation::where('service_id', $service->id)->get();
        if ($haircuts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No haircuts found',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $haircuts,
        ]);
    }

    public function search(Request $request)
    {
        $haircut = Variation::where('name', 'like', '%'.$request->name.'%')->get();
        if ($haircut->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No haircut found',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $haircut,
        ]);
    }
}
