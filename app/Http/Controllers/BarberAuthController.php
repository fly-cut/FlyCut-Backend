<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class BarberAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'last_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:barbers,email|email|max:40',
            'password' => 'required|string|confirmed|max:40',
            'birth_date' => 'required|date',
            'image' => 'required',
            'barbershop_id' => 'required'
        ]);
        
        $barber = Barber::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'rating' => 5,
            'birth_date' => $request->birth_date,
            'barbershop_id' => $request->barbershop_id
        ]);

        $path = $request->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path().'/images';
        $path->move($destinationPath,$filename);
        $barber->image = $filename;
        $barber->save();

        $response = [
            'barber' => $barber,
            'message' => 'barber registered successfully'
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $barber = Barber::where('email', $request->email)->first();
        if (! $barber || ! Hash::check($request->password, $barber->password)) {
            return response(
                [
                    'Response' => 'Please enter the right email or password!',
                ],
                401
            );
        }

        $token = $barber->createToken('barbertoken')->plainTextToken;

        $response = [
            'barber' => $barber,
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'Response' => 'Logged out',
        ];
    }
}
