<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarbershopOwner;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreBarbershopOwnerRequest;
use App\Http\Requests\UpdateBarbershopOwnerRequest;

class BarbershopOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBarbershopOwnerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BarbershopOwner $barbershopOwner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarbershopOwner $barbershopOwner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBarbershopOwnerRequest $request, BarbershopOwner $barbershopOwner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarbershopOwner $barbershopOwner)
    {
        //
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        $user = $request->user();
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        if (!Hash::check($current_password, $user->password)) {
            $message = [
                'message' => 'Password isn\'t correct'
            ];
            return response($message, 422);
        }
        $user->update(['password' => Hash::make($new_password)]);
        $message = [
            'message' => 'Password changed successfully'
        ];
        return response($message, 200);
    }
}
