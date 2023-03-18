<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'last_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:clients,email|email|max:40',
            'password' => 'required|string|confirmed|max:40',
            'birth_date' => 'required|date'
        ]);
        $fields['password'] = bcrypt($request->password);
        $client = Client::create($fields);
        $token = $client->createToken('Laravel Password Grant Client')->accessToken;
        $message = [
            'user' => $client,
            'token' => $token
        ];
        return response($message, 201);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $client = Client::where('email', $request->email)->first();
        if (!$client || Hash::check($fields['password'], $request->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }
        $token = $client->createToken('Laravel Password Grant Client')->accessToken;
        $message = [
            'user' => $client,
            'token' => $token
        ];
        return response(
            $message,
            201
        );
    }
    public function logout()
    {
        Auth::guard('client-api')->user()->tokens()->delete();
        return [
            'Response' => 'Logged out',
        ];
    }
}
