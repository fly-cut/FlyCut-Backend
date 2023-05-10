<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:admins,email|email|max:255',
            'password' => 'required|string|confirmed|max:255',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $token = $admin->createToken('AdminToken', ['admin'])->plainTextToken;
        $response = [
            'admin' => $admin,
            'token' => $token,
            'message' => 'Admin registered successfully',
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response([
                'response' => 'Please enter the right email or password!',
            ], 401);
        }

        $token = $admin->createToken('AdminToken', ['admin'])->plainTextToken;

        $response = [
            'admin' => $admin,
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
