<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function getSlots(Request $request)
    {
        //check if the request is valid
        $request->validate([
            'day' => 'required|date_format:Y-m-d',
            'barber_id' => 'required|exists:barbers,id',
            'barbershop_id' => 'required|exists:barbershops,id',
        ]);
        $barber = Barber::findOrFail($request->barber_id);
        $barbershop = $barber->barbershop;
        //check that barber really works in the barbershop
        if ($barbershop->id != $request->barbershop_id) {
            return response()->json([
                'message' => 'Barber does not work in this barbershop',
            ], 403);
        }
        $slots = Slot::where('start_time', 'LIKE', $request->day.'%')
            ->where('barber_id', $request->barber_id)
            ->get();
        //check that barber really works in the barbershop

        return response()->json($slots);
    }
}
