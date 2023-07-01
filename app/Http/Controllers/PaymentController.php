<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'phone_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'amount_cents' => 'required',
            'items' => 'required',
        ]);

        $request_new_token = Http::withHeaders(['content-type' => 'application/json'])
            ->post('https://accept.paymob.com/api/auth/tokens', [
                "api_key" => env('PAYMOB_API_KEY'),
                "username" => env('PAYMOB_USERNAME'),
                "password" => env('PAYMOB_PASSWORD'),
            ])->json();

        $auth_token = $request_new_token['token'];

        $reqeust_new_order = Http::withHeaders([
            'content-type' => 'application/json',
        ])->post('https://accept.paymob.com/api/ecommerce/orders', [
            "auth_token" => $auth_token,
            "delivery_needed" => "false",
            "amount_cents" => $request->amount_cents,
            "currency" => "EGP",
            "items" => $request->items,
        ])->json();

        $order_id = $reqeust_new_order['id'];

        $payment_key_request = Http::withHeaders([
            'content-type' => 'application/json',
        ])->post('https://accept.paymob.com/api/acceptance/payment_keys', [
            "auth_token" => $auth_token,
            "amount_cents" => $request->amount_cents,
            "expiration" => 3600,
            "order_id" => $order_id,
            "billing_data" => [
                "apartment" => "NA",
                "email" => $request->email,
                "floor" => "NA",
                "first_name" => $request->first_name,
                "street" => "NA", 
                "building" => "NA", 
                "phone_number" => $request->phone_number, 
                "shipping_method" => "NA", 
                "postal_code" => "NA", 
                "city" => "NA", 
                "country" => "NA", 
                "last_name" => $request->last_name, 
                "state" => "NA"
            ],
            "currency" => "EGP",
            "integration_id" => env('PAYMOB_INTEGRATION_ID'),
        ])->json();

        $payment_key = $payment_key_request['token'];

        return response()->json([
            'payment_key' => $payment_key,
            'order_id' => $order_id,
        ]);
    }

    public function callback(Request $request)
  {

      $data = $request->all();
      ksort($data);
      $hmac = $data['hmac'];
      $array = [
          'amount_cents',
          'created_at',
          'currency',
          'error_occured',
          'has_parent_transaction',
          'id',
          'integration_id',
          'is_3d_secure',
          'is_auth',
          'is_capture',
          'is_refunded',
          'is_standalone_payment',
          'is_voided',
          'order',
          'owner',
          'pending',
          'source_data_pan',
          'source_data_sub_type',
          'source_data_type',
          'success',
      ];
      $connectedString = '';
      foreach ($data as $key => $element) {
          if(in_array($key, $array)) {
              $connectedString .= $element;
          }
      }
      $secret = env('PAYMOB_HMAC');
      $hased = hash_hmac('sha512', $connectedString, $secret);
      if ( $hased == $hmac) {
          echo "secure" ; exit;
      }
      echo 'not secure'; exit;
  }
}
