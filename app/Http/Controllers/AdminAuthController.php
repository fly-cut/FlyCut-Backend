<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        //$admin = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($fields['password'], $admin->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        $token = $admin->createToken('Laravel Password Grant Client')->accessToken;

        $response = [
            'user' => $admin,
            'token' => $token
        ];
        return response($response, 201);
    }
    public function logout()
    {
        Auth::guard('admin-api')->user()->tokens()->delete();

        return [
            'Response' => 'Logged out',
        ];
    }
}
