<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarbershopOwner;
use Illuminate\Support\Facades\Hash;

class BarbershopOwnerAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'last_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:barbershop_owners,email|email|max:40',
            'password' => 'required|string|confirmed|max:40',
            'birth_date' => 'required|date'
        ]);

        $barbershop_owner = BarbershopOwner::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'birth_date' => $request->birth_date
        ]);
        if($request->image)
        {
            $path = $request->file('image');
            $filename = $path->getClientOriginalName();
            $destinationPath = public_path() . '/images';
            $path->move($destinationPath, $filename);
            $barbershop_owner->image = $filename;
            $barbershop_owner->save();
        }
        $token = $barbershop_owner->createToken('Laravel Password Grant BarbershopOwner')->accessToken;

        $response = [
            'barbershopOwner' => $barbershop_owner,
            'token' => $token,
            'message' => 'barbershop owner registered successfully'
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $barbershop_owner = BarbershopOwner::where('email', $request->email)->first();
        if (!$barbershop_owner || !Hash::check($request->password, $barbershop_owner->password)) {
            return response(
                [
                    'Response' => 'Please enter the right email or password!',
                ],
                401
            );
        }

        $token = $barbershop_owner->createToken('Laravel Password Grant BarbershopOwner')->accessToken;

        $response = [
            'barbershopOwner' => $barbershop_owner,
            'token' => $token,
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
