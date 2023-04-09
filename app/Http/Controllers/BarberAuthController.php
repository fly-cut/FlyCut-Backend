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
            'name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:barbers,email|email|max:40',
            'birth_date' => 'required|date',
            'image' => 'required',
            'barbershop_id' => 'required'
        ]);

        $barber = Barber::create([
            'name' => $request->first_name,
            'email' => $request->email,
            'rating' => 5,
            'birth_date' => $request->birth_date,
            'barbershop_id' => $request->barbershop_id
        ]);

        $path = $request->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path() . '/images';
        $path->move($destinationPath, $filename);
        $barber->image = $filename;
        $barber->save();

        $response = [
            'barber' => $barber,
            'message' => 'barber registered successfully'
        ];

        return response($response, 201);
    }
}
