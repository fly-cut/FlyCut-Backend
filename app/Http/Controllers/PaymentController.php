<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'delivery_needed' => 'required',
            'amount_cents' => 'required',
            'items' => 'required',
        ]);

        $request_new_token = Http::withHeaders(['content-type' => 'application/json'])
            ->post('https://accept.paymob.com/api/auth/tokens', [
                "api_key" => config('app.PAYMOB_API_KEY'),
                "username" => '01118080632',
                "password" => 'zfsQ4tD8/pju&Z/',
            ])->json();

        $auth_token = $request_new_token['token'];

        $reqeust_new_order = Http::withHeaders([
            'content-type' => 'application/json',
        ])->post('https://accept.paymob.com/api/ecommerce/orders', [
            "auth_token" => $auth_token,
            "delivery_needed" => $request->delivery_needed,
            "amount_cents" => $request->amount_cents,
            "currency" => "EGP",
            "items" => $request->items,
        ])->json();

        return response()->json([
            'success' => true,
            'data' => $reqeust_new_order,
        ]);

        
        
    }
}
