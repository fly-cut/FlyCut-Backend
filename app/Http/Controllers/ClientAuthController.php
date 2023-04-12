<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ClientAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:clients,email|email|max:255',
            'password' => 'required|string|confirmed|max:40'
        ]);
        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = $client->createToken('ClientToken', ['role:client'])->plainTextToken;
        $response = [
            'client' => $client,
            'token' => $token,
            'message' => 'Client registered successfully'
        ];
        return response($response, 201);
    }
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $client = Client::where('email', $request->email)->first();
        if (!$client || Hash::check($request->password, $client->password)) {
            return response([
                'response' => 'Please enter the right email or password!'
            ], 401);
        }
        $token = $client->createToken('ClientToken', ['role:client'])->plainTextToken;
        $response = [
            'client' => $client,
            'token' => $token
        ];
        return response($response, 200);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'response' => 'Logged out',
        ];
    }
}
