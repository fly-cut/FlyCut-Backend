<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\BarberRating;
use Illuminate\Http\Request;

class BarberRatingController extends Controller
{
    /**
     * Store a new rating for a barber.
     *
     * @OA\Post(
     *     path="/api/barber/ratings",
     *     summary="Store a new rating for a barber.",
     *     description="Store a new rating for a barber with barber ID, client ID, rating value, and review.",
     *     tags={"Barber_Ratings"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"barber_id", "client_id", "rating"},
     *
     *             @OA\Property(
     *                 property="barber_id",
     *                 type="integer",
     *                 description="The ID of the barber being rated."
     *             ),
     *             @OA\Property(
     *                 property="client_id",
     *                 type="integer",
     *                 description="The ID of the client submitting the rating."
     *             ),
     *             @OA\Property(
     *                 property="rating",
     *                 type="integer",
     *                 description="The rating value (1-5)."
     *             ),
     *             @OA\Property(
     *                 property="review",
     *                 type="string",
     *                 nullable=true,
     *                 description="The review text (optional)."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Rating created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="barber_rating"
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
        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'client_id' => 'required|exists:clients,id',
            'reservation_id' => 'required|exists:reservations,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $barberRating = BarberRating::create($request->all());
        $barber = Barber::find($request->barber_id);
        $barber->rating = $barber->ratings()->avg('rating');
        $barber->rating_count = $barber->ratings()->count();
        $barber->save();

        return response()->json($barberRating, 201);
    }

    /**
     * Update an existing barber rating.
     *
     * @OA\Put(
     * path="/api/barber/ratings/{id}",
     * summary="Update an existing barber rating.",
     * description="Update an existing barber rating with rating ID, rating value, and review.",
     * tags={"Barber_Ratings"},
     *
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="The ID of the rating to update.",
     * required=true,
     *
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     *
     * @OA\RequestBody(
     * required=true,
     *
     * @OA\JsonContent(
     * required={"rating"},
     *
     * @OA\Property(
     * property="rating",
     * type="integer",
     * description="The rating value (1-5)."
     * ),
     * @OA\Property(
     * property="review",
     * type="string",
     * nullable=true,
     * description="The review text (optional)."
     * )
     * )
     * ),
     *
     * @OA\Response(
     * response=200,
     * description="Rating updated successfully.",
     *
     * @OA\JsonContent(
     *
     * @OA\Property(
     * property="barber_rating"
     * )
     * )
     * ),
     *
     * @OA\Response(
     * response=422,
     * description="Unprocessable Entity",
     *
     * @OA\JsonContent(
     *
     * @OA\Property(
     * property="message",
     * type="string",
     * example="The given data was invalid."
     * ),
     * @OA\Property(
     * property="errors",
     * type="object"
     * )
     * )
     * ),
     *
     * @OA\Response(
     * response=404,
     * description="Rating not found.",
     *
     * @OA\JsonContent(
     *
     * @OA\Property(
     * property="message",
     * type="string",
     * example="Rating not found."
     * )
     * )
     * ),
     * security={
     * {"bearerAuth": {}}
     * }
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $barberRating = BarberRating::find($id);
        $barberRating->update($request->all());
        $barber = Barber::find($barberRating->barber_id);
        $barber->rating = $barber->ratings()->avg('rating');
        $barber->rating_count = $barber->ratings()->count();
        $barber->save();

        return response()->json($barberRating, 200);
    }

    /**
     * Delete a rating for a barber.
     *
     * @OA\Delete(
     *     path="/api/barber/ratings/{id}",
     *     summary="Delete a rating for a barber.",
     *     description="Delete a rating for a barber with the given ID.",
     *     tags={"Barber_Ratings"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the rating to delete.",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Rating deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rating not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Rating not found."
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy($id)
    {
        $barberRating = BarberRating::find($id);
        if (! $barberRating) {
            return response()->json(['message' => 'Rating not found.'], 404);
        }
        $barberRating->delete();
        $barber = Barber::find($barberRating->barber_id);
        if ($barber->ratings()->avg('rating')) {
            $barber->rating = $barber->ratings()->avg('rating');
        } else {
            $barber->rating = 5.0;
        }
        $barber->rating_count = $barber->ratings()->count();
        $barber->save();

        return response()->json(['message' => 'Rating deleted successfully.'], 200);
    }

    /**
     * Get all ratings for a specific barber.
     *
     * @OA\Get(
     *     path="/api/barber/ratings/{id}",
     *     summary="Get all ratings for a specific barber.",
     *     description="Get all ratings for a specific barber using the barber's ID.",
     *     tags={"Barber_Ratings"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The ID of the barber.",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Ratings retrieved successfully.",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
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
     *                 property="message",
     *                 type="string",
     *                 example="Barber not found."
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function getRatings($id)
    {
        $barber = Barber::find($id);
        if (! $barber) {
            return response()->json(['message' => 'Barber not found.'], 404);
        }
        $barberRatings = BarberRating::where('barber_id', $id)->get();

        return response()->json($barberRatings, 200);
    }
}
