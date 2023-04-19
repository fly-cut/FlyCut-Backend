<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Http\Requests\StoreBarberRequest;
use App\Http\Requests\UpdateBarberRequest;
use App\Http\Resources\BarberResource;
use App\Models\Barbershop;

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
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
            ]
        );
        $image = $request->file('image');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/barbers'), $image_name);

        $barber = Barber::create([
            'name' => $request->name,
            'barbershop_id' => $request->barbershop_id,
            'image' => $image_name,
        ]);
        $message = [
            'message' => 'Barber created successfully',
            'barber' => $barber
        ];
        return response($message, 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarberRequest $request, Barber $barber)
    {
        //
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