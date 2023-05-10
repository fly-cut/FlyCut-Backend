<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarberRequest;
use App\Models\Barber;
use App\Models\Barbershop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BarberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    //get all barbers of a barbershop
    public function getBarbersOfBarbershop($barbershop_id)
    {
        $barberShop = BarberShop::find($barbershop_id);

        return $barberShop->barbers;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBarberRequest $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'barbershop_id' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]
        );
        $image = $request->file('image');
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('images'), $image_name);

        $barber = Barber::create([
            'name' => $request->name,
            'barbershop_id' => $request->barbershop_id,
            'image' => $image_name,
        ]);
        $message = [
            'message' => 'Barber created successfully',
            'barber' => $barber,
        ];

        return response($message, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($request, $barber_id)
    {
        $this->validate($request,
            [
                'name' => 'required',
                'barbershop_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);
        $barber = Barber::find($barber_id);
        $barber->name = $request->name;
        $barber->barbershop_id = $request->barbershop_id;

        if ($request->hasFile('image')) {
            $image_path = public_path('images/'.$barber->image);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $image = $request->file('image');
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('/images'), $image_name);
            $barber->image = $image_name;
        }
        $barber->save();

        return response()->json(['message' => 'Barber updated successfully']);



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barber $barber)
    {
        $barber->delete();

        return response()->json(['message' => 'Barber deleted successfully']);
    }
}
