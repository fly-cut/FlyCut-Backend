<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        $user = $request->user();
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        if (!Hash::check($current_password, $user->password)) {
            $message = [
                'message' => 'Password isn\'t correct',
            ];

            return response($message, 422);
        }
        $user->update(['password' => Hash::make($new_password)]);
        $message = [
            'message' => 'Password changed successfully',
        ];

        return response($message, 200);
    }

    //update profile
    public function updateProfile(Request $request)
    {
        //access name from request object
        $formData = $request->validate([
            'name' => 'string',
            'email' => 'email',
        ]);

        $user = $request->user();
        if ($request->file('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images/'), $image_name);
            $formData['image'] = $image_name;
        }
        $user->update($formData);
        $message = [
            'message' => 'Profile updated successfully',
        ];

        return response($message, 200);
    }
    public function getReservations(Request $request)
    {
        $user = $request->user();
        $user->id;
        $reservations = Reservation::where('user_id', $user->id)->orderBy('date')->get();
        $data = [];
        foreach ($reservations as $reservation) {
            $reservationId = $reservation->id;
            $services = Service::whereHas('reservations', function ($query) use ($reservationId) {
                $query->where('reservation_id', $reservationId);
            })->get();
            $servicedata = [];
            foreach ($services as $service) {
                $variations = Service::whereHas('reservations', function ($query) use ($reservationId) {
                    $query->where('reservation_id', $reservationId);
                })->with('variations')->get()->pluck('variations')->flatten();
                $service = $service->toArray();
                $variations = $variations->toArray();
                $servicevariatons = array_merge($service, $variations);
                $servicedata[] = [
                    "service" => $servicevariatons
                ];
            }
            $element = [
                'reservation' => $reservation,
                "services" => $servicedata
            ];
            $data[] = $element;
        }
        $jsonObject = json_encode($data);
        return $jsonObject;
    }
}
