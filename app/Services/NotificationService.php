<?php

namespace App\Services;

use App\Models\Barber;
use App\Models\Barbershop;
use App\Models\Reservation;
use App\Models\BarbershopOwner;

class NotificationService
{
    public static function sendNotification(Reservation $reservation)
    {
        $SERVER_API_KEY = ' AAAAlzQrgRs:APA91bHpRF--tL-XqitG5ADoIaf7rl0fbtYBMgDxAVlLGoj5G9v9axAjDa0Ufb4rr3r369N0pX9ugr2zRUB2omd6UUna_Tx1xtSabPYmrithHV9UOArZDGglRbD1j7Jrbqg3WsJvnQ8Y';

        $barber_id = $reservation->barber_id;
        $barber = Barber::find($barber_id);
        $barbershop_id = $barber->barbershop_id;
        $barbershop = Barbershop::find($barbershop_id);
        $barbershop_owner_id = $barbershop->barbershop_owner_id;
        $barbershop_owner = BarbershopOwner::find($barbershop_owner_id);

        $data = [

            "registration_ids" => [
                $barbershop_owner->token
            ],

            "notification" => [

                "title" => 'New Bookings Awaiting ✂',

                "body" => $barber->name . ' has a new booking at Your Barbershop.' . "\nGo and check it out!",

                "sound" => "default" // required for sound on ios

            ],

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }
}
