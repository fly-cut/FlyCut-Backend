<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BarbershopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexBarbershop()
    {
        return Barbershop::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addBarbershop(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'image' => 'required',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        $barbershop = new Barbershop;
        $barbershop->name = $request->name;
        $barbershop->description = $request->description;
        $barbershop->address = $request->address;
        $barbershop->longitude = $request->longitude;
        $barbershop->latitude = $request->latitude;
        $barbershop->city = $request->city;
        $barbershop->barbershop_owner_id = Auth::id();

        $path = $request->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path().'/images';
        $path->move($destinationPath, $filename);
        $barbershop->image = $filename;

        $barbershop->save();

        return response()->json([
            'status' => 200,
            'message' => 'Barbershop has been added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function showBarbershop($barbershop_id)
    {
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be shown!',
            ]);
        }

        return response()->json([
            'status' => 200,
            'barbershop' => $barbershop,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateBarbershop(Request $request, $barbershop_id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'image' => 'required',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        $barbershop = Barbershop::find($barbershop_id);
        if (! $barbershop || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be updated!',
            ]);
        }
        $barbershop->name = $request->name;
        $barbershop->description = $request->description;
        $barbershop->address = $request->address;
        $barbershop->city = $request->city;
        $barbershop->longitude = $request->longitude;
        $barbershop->latitude = $request->latitude;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('images/'.$barbershop->image))) {
                File::delete(public_path('images/'.$barbershop->image));
                $path = $request->file('image');
                $filename = $path->getClientOriginalName();
                $destinationPath = public_path().'/images';
                $path->move($destinationPath, $filename);
                $barbershop->image = $filename;
            }
        }

        $barbershop->update();

        return response()->json([
            'status' => 200,
            'message' => 'Barbershop updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyBarbershop($barbershop_id)
    {
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be deleted!',
            ]);
        } else {
            $barbershop->delete();

            return response()->json([
                'status' => 200,
                'message' => 'The barbershop has been deleted!',
            ]);
        }
    }

    //write a function to add existing services to a barbershop and they should be connected via the pivot table, the services should be taken as array of ids also it should check if the service ids are valid and existing in the services table
    public function addServicesToBarbershop(Request $request, $barbershop_id)
    {
        $this->validate($request, [
            'services' => 'required|array',
        ]);
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to add services to it!',
            ]);
        }
        $barbershop->services()->sync($request->services);

        return response()->json([
            'status' => 200,
            'message' => 'Services added successfully to the barbershop',
        ]);
    }
}
