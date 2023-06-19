<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function getSlots(Request $request)
    {
        //check if the request is valid
        $request->validate([
            'day' => 'required|date_format:Y-m-d',
            'barber_id' => 'required|exists:barbers,id'
        ]);
        $slots = Slot::where('start_time', 'LIKE', $request->day . '%')
            ->where('barber_id', $request->barber_id)
            ->get();
        return response()->json($slots);
    }
}
