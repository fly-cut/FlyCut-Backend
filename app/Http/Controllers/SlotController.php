<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index()
    {
        return Slot::all();
    }

    public function show($id)
    {
        return response()->json(Slot::findOrFail($id));
    }

    public function getSlots(Request $request)
    {
        // Check if the request is valid
        $request->validate([
            'day' => 'required|date_format:Y-m-d',
            'barber_id' => 'required|exists:barbers,id',
            'barbershop_id' => 'required|exists:barbershops,id',
        ]);

        $barber = Barber::findOrFail($request->barber_id);
        $barbershop = $barber->barbershop;

        // Check that the barber really works in the barbershop
        if ($barbershop->id != $request->barbershop_id) {
            return response()->json([
                'message' => 'Barber does not work in this barbershop',
            ], 403);
        }

        $slots = Slot::where('start_time', 'LIKE', $request->day.'%')
            ->where('barber_id', $request->barber_id)
            ->get();
        $data = [];
        foreach ($slots as $slot) {
            $slotData = [
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'slot_id' => $slot->id,
                'barber_id' => $slot->barber_id,
                'status' => $slot->status,
            ];
            if ($slot->status == 'reserved') {
                $reservation_id = $slot->reservation_id;
                $reservation = Reservation::findOrFail($reservation_id);

                $client_id = $reservation->user_id;
                $client = Client::findOrFail($client_id);
                $slotData['reservation'] = $reservation;
                $slotData['client'] = $client;
            }

            $data[] = ['slot' => $slotData];
        }

        return response()->json($data);
    }

    public function changeStatusToBusy(Request $request)
    {
        $startTime = $request->input('start_time');
        $start = Carbon::parse($startTime);
        $end_time = $request->input('end_time');
        $end = Carbon::parse($end_time);
        $barber_id = $request->input('barber_id');
        $slot = Slot::create([
            'start_time' => $start,
            'end_time' => $end,
            'barber_id' => $barber_id,
            'reservation_id' => null,
        ]);
        $slot->status = 'busy';
        $slot->save();
        $message = 'Slot status changed to busy';

        return response()->json(['message' => $message, 'slot' => $slot]);
    }

    public function changeStatusToFree(Request $request)
    {
        $slot_id = $request->input('slot_id');
        $barber_id = $request->input('barber_id');
        $slot = Slot::where('id', $slot_id)->where('barber_id', $barber_id)->firstOrFail();
        if ($slot->status == 'busy') {
            $slot->delete();
            $message = 'Slot status changed to free';

            return response()->json(['message' => $message]);
        }
    }
}
