<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'auth_token' => 'required',
            'delivery_needed' => 'required',
            'amount_cents' => 'required',
            'currency' => 'required',
            'items.*.name' => 'required',
            'items.*.[amount_cents]' => 'required',
            'items.*.[description]' => 'required',
            'items.*.[quantity]' => 'required',
        ]);

        $request_new_token = Http::withHeaders(['content-type' => 'application/json'])
            ->post('https://accept.paymob.com/api/auth/tokens', [
                "api_key" => config('app.PAYMOB_API_KEY'),
                "username" => '01118080632',
                "password" => 'zfsQ4tD8/pju&Z/',
            ])->json();
        
        
    }
}
