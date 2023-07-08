<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Barbershop;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Slot;
use App\Models\Variation;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    private $reservationService;
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
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

    public function updateProfile(Request $request)
    {
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
        $user->update(['email_verified_at' => null]);

        $message = [
            'message' => 'Profile updated successfully',
            'client' => $user,
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
            //ReservationController::getStatus($reservation);
            $this->reservationService->getStatus($reservation);
            $reservationId = $reservation->id;
            $services = Service::whereHas('reservations', function ($query) use ($reservationId) {
                $query->where('reservation_id', $reservationId);
            })->get();

            $servicedata = [];

            foreach ($services as $service) {
                $variations = Variation::where('service_id', $service->id)->get();
                $variationData = $variations->toArray();

                $serviceData = $service->toArray();
                $serviceData['variation'] = $variationData[0] ?? null;

                $servicedata[] = $serviceData;
            }
            $barbershop = Barbershop::find($reservation->barbershop_id);
            $barber = Barber::find($reservation->barber_id);

            $element = [
                'reservation' => $reservation,
                'services' => $servicedata,
                'barbershop_image' => $barbershop->image,
                'barbershop_name' => $barbershop->name,
                'barbershop_city' => $barbershop->city,
                'barber_image' => $barber->image,
            ];

            $data[] = $element;
        }

        return response()->json($data);
    }

    public function checkBarberAvailability(Request $request)
    {
        $barberId = $request->input('barberId');
        $start_time = $request->input('start_time');
        $numberOfSlots = $request->input('numberOfSlots');
        // Parse the provided time string
        $startTime = Carbon::parse($start_time);

        // Calculate the end time based on the number of slots
        $endTime = $startTime->copy()->addMinutes($numberOfSlots * 15);

        // Fetch existing slots for the given barber within the provided time range
        $existingSlots = Slot::where('barber_id', $barberId)
            ->where('start_time', '>=', $startTime)
            ->where('end_time', '<=', $endTime)
            ->orderBy('start_time')
            ->get();

        $prevEndTime = null;
        foreach ($existingSlots as $slot) {
            if ($slot->start_time > $endTime) {
                break; // No more overlapping slots
            }

            if ($slot->start_time > $prevEndTime) {
                $message = 'No slots available';

                return response($message, 401); // Gap found, slots are not after each other
            }

            $prevEndTime = $slot->end_time;
        }
        $message = 'Slots available';

        return response($message, 200);
    }
}
