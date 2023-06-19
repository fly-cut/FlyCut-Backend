<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Barber;
use App\Models\Barbershop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use DateTime;

class BarberController extends Controller
{
    /**
     * Store a new barber.
     *
     * @OA\Post(
     *     path="/api/barbers",
     *     summary="Store a new barber.",
     *     description="Store a new barber with name, barbershop id, and image.",
     *     tags={"Barber"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 required={"name", "barbershop_id", "image"},
     *
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name of the barber."
     *                 ),
     *                 @OA\Property(
     *                     property="barbershop_id",
     *                     type="integer",
     *                     description="The ID of the barbershop that the barber belongs to."
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="file",
     *                     description="The image of the barber."
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Barber created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Barber created successfully"
     *             ),
     *             @OA\Property(
     *                 property="barber"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function store(Request $request)
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
        $image_name = time() . '.' . $image->getClientOriginalExtension();
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
     * Update an existing barber.
     *
     * @OA\Put(
     *     path="/api/barbers/{barber_id}",
     *     summary="Update an existing barber.",
     *     description="Update an existing barber with name, barbershop id, and image.",
     *     tags={"Barber"},
     *
     *     @OA\Parameter(
     *         name="barber_id",
     *         in="path",
     *         required=true,
     *         description="The ID of the barber to update.",
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 required={"name", "barbershop_id"},
     *
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name of the barber."
     *                 ),
     *                 @OA\Property(
     *                     property="barbershop_id",
     *                     type="integer",
     *                     description="The ID of the barbershop that the barber belongs to."
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="file",
     *                     description="The image of the barber."
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Barber updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Barber updated successfully"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Barber not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Barber not found"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(Request $request, $barber_id)
    {
        $this->validate(
            $request,
            [
                'name' => 'required',
                'barbershop_id' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]
        );
        $barber = Barber::find($barber_id);
        $barber->name = $request->name;
        $barber->barbershop_id = $request->barbershop_id;

        if ($request->hasFile('image')) {
            $image_path = public_path('images/' . $barber->image);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/images'), $image_name);
            $barber->image = $image_name;
        }
        $barber->save();

        return response()->json(['message' => 'Barber updated successfully'], 200);
    }

    /**
     * Delete a barber.
     *
     * @OA\Delete(
     *     path="/api/barbers/{barber_id}",
     *     summary="Delete a barber.",
     *     description="Delete a barber by ID.",
     *     tags={"Barber"},
     *
     *     @OA\Parameter(
     *         name="barber_id",
     *         in="path",
     *         required=true,
     *         description="ID of the barber to delete",
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Barber deleted successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The barber has been deleted!"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Barber not found.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 example="No barber found to be deleted!"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy($barber_id)
    {
        $barber = Barber::find($barber_id);
        if (is_null($barber) || empty($barber)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barber found to be deleted!',
            ], 404);
        } else {
            $barber->delete();

            return response()->json([
                'status' => 200,
                'message' => 'The barber has been deleted!',
            ], 200);
        }
    }
    public function checkAvailability(Request $request)
    {
        $startTime = $request->input('start_time');
        $numberOfSlots = $request->input('number_of_slots');
        $barberId = $request->input('barber_id');
        // Parse the provided time string
        $startTime = Carbon::parse($startTime);

        // Calculate the end time based on the number of slots
        $endTime = $startTime->copy()->addMinutes($numberOfSlots * 15);

        // Fetch existing slots for the given barber within the provided time range
        $existingSlots = Slot::where('barber_id', $barberId)
            ->where('start_time', '>=', $startTime)
            ->where('end_time', '<=', $endTime)
            ->orderBy('start_time')
            ->get();

        // Check if there are any overlapping slots
        $prevEndTime = null;
        foreach ($existingSlots as $slot) {
            if ($slot->start_time > $endTime) {
                break; // No more overlapping slots
            }

            if ($slot->start_time > $prevEndTime) {
                $message = 'No slots available';
                return response($message, 201); // Gap found, slots are not after each other
            }

            $prevEndTime = $slot->end_time;
        }
        $message = 'Slots available';
        return response($message, 201);
    }
}