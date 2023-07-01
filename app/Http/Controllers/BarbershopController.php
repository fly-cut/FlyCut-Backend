<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BarbershopController extends Controller
{
    /**
     * Get all barbershops.
     *
     * @OA\Get(
     *     path="/api/barbershops",
     *     summary="Get all barbershops",
     *     description="Returns a list of all barbershops",
     *     tags={"Barbershop"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of barbershops",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Barbershop name"),
     *                 @OA\Property(property="image", type="string", example="barbershop.jpg"),
     *                 @OA\Property(property="description", type="string", example="Barbershop description"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="city", type="string", example="Anytown"),
     *                 @OA\Property(property="longitude", type="number", format="float", example=-73.12345),
     *                 @OA\Property(property="latitude", type="number", format="float", example=40.12345),
     *             ),
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function indexBarbershop()
    {
        return Barbershop::all();
    }

    /**
     * Add a new barbershop.
     *
     * @OA\Post(
     *     path="/api/barbershops",
     *     summary="Add a new barbershop",
     *     description="Creates a new barbershop based on the provided parameters",
     *     tags={"Barbershop"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Barbershop details",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", example="Barbershop name"),
     *             @OA\Property(property="image", type="string", format="binary", description="Image file of the barbershop"),
     *             @OA\Property(property="description", type="string", example="Barbershop description"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="city", type="string", example="Anytown"),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.12345),
     *             @OA\Property(property="latitude", type="number", format="float", example=42.12345),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Barbershop added successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Barbershop has been added successfully"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example=422),
     *             @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}}),
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function addBarbershop(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
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
            'barbershop' => $barbershop,
        ], 200);
    }

    /**
     * Show a barbershop by ID.
     *
     * @OA\Get(
     *     path="/api/barbershops/{barbershop_id}",
     *     summary="Show a barbershop by ID",
     *     description="Returns a specific barbershop by ID",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the barbershop to show",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="The requested barbershop",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Barbershop name"),
     *             @OA\Property(property="image", type="string", example="barbershop.jpg"),
     *             @OA\Property(property="description", type="string", example="Barbershop description"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="city", type="string", example="Anytown"),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.12345),
     *             @OA\Property(property="latitude", type="number", format="float", example=40.12345),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2021-01-01 00:00:00"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2021-01-01 00:00:00"),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Barbershop not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="errors", type="string", example="No barbershop found to be shown!"),
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function showBarbershop($barbershop_id)
    {
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be shown!',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'barbershop' => $barbershop,
        ], 200);
    }

    /**
     * Update a barbershop.eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzNSIsImp0aSI6IjI5NzZlNGU4MTkxNGMwNWM2NzZlZGQ2MjRmYTJlMThiZDE0YjhmNGI2NjZhM2UxZjU5NTQ2Mjc1ZGRjOTE2ZDFjZWQ0Mzc3MjIwMjg2MTVhIiwiaWF0IjoxNjg3ODc3MjA1LjcyMzg0MywibmJmIjoxNjg3ODc3MjA1LjcyMzg0OCwiZXhwIjoxNzE5NDk5NjA1LjcxNzg2MSwic3ViIjoiMSIsInNjb3BlcyI6W119.AaW_MzaNTtF0BVfbRe2F12E996FXIRTiCq2nANkDdFCpkoxLKisj_9qRF2sMCXJaXYmaxaJHE_WDk_a6STWPqDWrZqbm_9UAhww2XJ-2_44JO5imnfQZhp6uL2UctmJ9q7GnjXfQcY_GIuI5JXOuxIy9Dk0uk9iIG9fbOEfdS5JRa_2muxEpHnb8zko6G-3oGihAUU02Js6qC-hkoBKhoHEVRHkZ-lcDuCGTdTF2xTOlrisGx0u3AXd2qKmkbYY46nqCon9oY7Bf8hgLQmxvE8ETXD_oEPzynShoyaFV5Hgl6sm8N7jmzvt__9IajkS3Nlr7pOaXy-cY3-nKVFSU7gfA5ojYulbJ5NNDW1lIwvZYu40XHjjSzL73YIYFdeVSbP0QouBmYbqth4FuPIhlNeUBBW_BVOO_KyHoK-mQrM-h0asXiiCl7nlAg2kCQgpUkofYoRODwiRPbsJ78FJLKIbz2vku3blko2Mj5y0pwVBy7HuZv8NupBC_BhfDvqzeJUGbBjHhkxiuu9QXZZg46qPoSVbMJ_13EPJEffXxl0W5Hie_tEHqwNRaMZ7H5WnZ_8IM_9wlTcR-rD4ee1wW-S3RVLqO6WUHJ_En_SioIzWWZW_0riRoic9VCRT38YwH7v9hOs2Blwl3gncJBGDmPAZSv369fux9YZwAdbd0K-Q
     *
     * @OA\Put(
     *     path="/api/barbershops/{barbershop_id}",
     *     summary="Update a barbershop",
     *     description="Updates an existing barbershop",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop to be updated",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Fields to be updated",
     *
     *         @OA\JsonContent(
     *             required={"name","image","description","address","city","longitude","latitude"},
     *
     *             @OA\Property(property="name", type="string", example="John's Barbershop"),
     *             @OA\Property(property="image", type="file"),
     *             @OA\Property(property="description", type="string", example="The best barbershop in town"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="city", type="string", example="New York"),
     *             @OA\Property(property="longitude", type="number", example="-73.935242"),
     *             @OA\Property(property="latitude", type="number", example="40.730610")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Barbershop updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Barbershop updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No barbershop found to be updated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="errors", type="string", example="No barbershop found to be updated!")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function updateBarbershop(Request $request, $barbershop_id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'longitude' => 'numeric',
            'latitude' => 'numeric',
        ]);

        $barbershop = Barbershop::find($barbershop_id);
        if (! $barbershop || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be updated!',
            ], 404);
        }
        $barbershop->name = $request->name;
        $barbershop->description = $request->description;
        $barbershop->address = $request->address;
        $barbershop->city = $request->city;
        if ($request->longitude != null && $request->latitude != null) {
            $barbershop->longitude = $request->longitude;
            $barbershop->latitude = $request->latitude;
        }
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
            'barbershop' => $barbershop,
        ], 200);
    }

    /**
     * Delete a barbershop.
     *
     * @OA\Delete(
     *     path="/api/barbershops/{barbershop_id}",
     *     summary="Delete a barbershop",
     *     description="Deletes a barbershop from the database",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop to be deleted",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success message",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The barbershop has been deleted!"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Barbershop not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 example="No barbershop found to be deleted!"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroyBarbershop($barbershop_id)
    {
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to be deleted!',
            ], 404);
        } else {
            $barbershop->delete();

            return response()->json([
                'status' => 200,
                'message' => 'The barbershop has been deleted!',
            ], 200);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/barbershops/{barbershop_id}/services",
     *     summary="Add services to a barbershop",
     *     description="Adds services to the specified barbershop",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         description="Services to be added",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="services",
     *                 type="array",
     *
     *                 @OA\Items(
     *                     type="integer",
     *                     format="int64"
     *                 ),
     *                 example="[1, 2, 3]"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Services added successfully to the barbershop",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Services added successfully to the barbershop"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No barbershop found to add services to it!",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 example="No barbershop found to add services to it!"
     *             )
     *         )
     *     ),
     *    security={
     *        {"bearerAuth": {}}
     *   }
     * )
     */
    public function addServicesToBarbershop(Request $request)
    {
        $this->validate($request, [
            'services' => 'required|array',
        ]);

        $barbershop = Barbershop::where('barbershop_owner_id', Auth::user()->id)->first();
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to add services to it!',
            ], 404);
        }
        foreach ($request->services as $service) {
            if (! Service::find($service)) {
                return response()->json([
                    'status' => 404,
                    'errors' => 'No service found to be added!',
                ], 404);
            }
            $barbershop->services()->attach($service);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Services added successfully to the barbershop',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/barbershops/{barbershop_id}/services/{service_id}",
     *     summary="Remove a service from a barbershop",
     *     description="Removes a service from the specified barbershop",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="service_id",
     *         in="path",
     *         description="ID of the service to be removed",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Service removed successfully from the barbershop",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Service removed successfully from the barbershop"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No barbershop found to remove service from it!",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 example="No barbershop found to remove service from it!"
     *             )
     *         )
     *     ),
     *   security={
     *       {"bearerAuth": {}}
     *  }
     * )
     */
    public function removeServicesFromBarbershop(Request $request)
    {
        $this->validate($request, [
            'services' => 'required|array',
            'services.*' => 'required|numeric',
        ]);

        $barbershop = Barbershop::where('barbershop_owner_id', Auth::user()->id)->first();

        if (is_null($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to remove services from!',
            ], 404);
        }

        foreach ($request->services as $service) {
            if (! Service::find($service)) {
                return response()->json([
                    'status' => 404,
                    'errors' => 'No service found to be removed!',
                ], 404);
            }
            $barbershop->services()->detach($service);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Services removed successfully from the barbershop',
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/barbershops/{barbershop_id}/services/{service_id}",
     *     summary="Edit the price and slots of a service in a barbershop",
     *     description="Updates the price and slots of the specified service in the specified barbershop",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="service_id",
     *         in="path",
     *         description="ID of the service",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         description="New price and slots of the service",
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="price",
     *                 type="number",
     *                 format="float",
     *                 example=19.99
     *             ),
     *             @OA\Property(
     *                 property="slots",
     *                 type="integer",
     *                 example=5
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Service price and slots updated successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Service price and slots updated successfully"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No barbershop found to edit its services!",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 example="No barbershop found to edit its services!"
     *             )
     *         )
     *     ),
     *    security={
     *      {"bearerAuth": {}}
     * }
     * )
     */
    public function editServicePriceAndSlots(Request $request)
    {
        $this->validate($request, [
            'services' => 'required|array',
            'services.*.id' => 'required|numeric',
            'services.*.price' => 'required|numeric',
            'services.*.slots' => 'required|numeric',
        ]);

        $barbershop = Barbershop::where('barbershop_owner_id', Auth::user()->id)->first();

        if (is_null($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to edit its services!',
            ], 404);
        }

        foreach ($request->services as $service) {
            $service_id = $service['id'];
            $price = $service['price'];
            $slots = $service['slots'];
            if (! Service::find($service_id)) {
                return response()->json([
                    'status' => 404,
                    'errors' => 'No service found to be edited!',
                ], 404);
            }

            $barbershop->services()->updateExistingPivot($service_id, compact('price', 'slots'));
        }

        return response()->json([
            'status' => 200,
            'message' => 'Service prices and slots updated successfully',
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/barbershops/{barbershop_id}/services",
     *     summary="Get barbershop services with price and slots",
     *     description="Retrieves the services of the specified barbershop along with their prices and available slots",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Returns the services of the barbershop along with their prices and available slots",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="services",
     *                 type="array",
     *
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Haircut"
     *                     ),
     *                     @OA\Property(
     *                         property="price",
     *                         type="integer",
     *                         example=20
     *                     ),
     *                     @OA\Property(
     *                         property="slots",
     *                         type="integer",
     *                         example=5
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No barbershop found to get its services!",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 example="No barbershop found to get its services!"
     *             )
     *         )
     *     ),
     *   security={
     *     {"bearerAuth": {}}
     *  }
     * )
     */
    public function getBarbershopServicesWithPriceAndSlots($barbershop_id)
    {
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to get its services!',
            ], 404);
        }
        $services = $barbershop->services()->withPivot('price', 'slots')->get();

        return response()->json([
            'status' => 200,
            'services' => $services,
        ], 200);
    }

    /**
     * Get reviews for a barbershop.
     *
     * @OA\Get(
     *     path="/api/barbershops/{barbershop_id}/reviews",
     *     summary="Get reviews for a barbershop",
     *     description="Returns a list of reviews for the specified barbershop",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop to get reviews for",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of reviews",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="barber_id", type="integer", example=1),
     *                 @OA\Property(property="client_id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="integer", example=4),
     *                 @OA\Property(property="review", type="string", example="Great haircut!")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Barbershop not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="errors", type="string", example="No barbershop found to get its reviews!")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function getBarbershopReviews($barbershop_id)
    {
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to get its reviews!',
            ], 404);
        }
        $reviews = $barbershop->reviews;

        return response()->json([
            'status' => 200,
            'reviews' => $reviews,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/barbershops/{barbershop_id}/barbers",
     *     summary="Get barbers of a specific barbershop",
     *     description="Retrieves the list of barbers who work at a specific barbershop",
     *     operationId="getBarbersOfBarbershop",
     *     tags={"Barbershop"},
     *
     *     @OA\Parameter(
     *         name="barbershop_id",
     *         in="path",
     *         description="ID of the barbershop to get the barbers from",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of barbers",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="barbers",
     *                 type="array",
     *
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="image",
     *                         type="string",
     *                         example="https://example.com/avatar.png"
     *                     ),
     *                    @OA\Property(
     *                        property="rating",
     *                        type="integer",
     *                        example=4
     *                     ),
     *                    @OA\Property(
     *                        property="rating_count",
     *                        type="integer",
     *                        example=10
     *                     ),
     *                    @OA\Property(
     *                        property="barbershop_id",
     *                        type="integer",
     *                        example=1
     *                     ),
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="No barbershop found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=404
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="string",
     *                 example="No barbershop found to get its barbers!"
     *             )
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function getBarbersOfBarbershop($barbershop_id)
    {
        $barbershop = Barbershop::find($barbershop_id);
        if (is_null($barbershop) || empty($barbershop)) {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found to get its barbers!',
            ], 404);
        }
        $barbers = $barbershop->barbers;

        return response()->json([
            'status' => 200,
            'barbers' => $barbers,
        ], 200);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->get('searchQuery');
        $userLongitude = $request->get('userLongitude');
        $userLatitude = $request->get('userLatitude');
        $barbershops = Barbershop::query()
            ->where(function ($query) use ($searchQuery) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($searchQuery).'%'])
                    ->orWhereRaw('LOWER(city) LIKE ?', ['%'.strtolower($searchQuery).'%'])
                    ->orWhereRaw('LOWER(address) LIKE ?', ['%'.strtolower($searchQuery).'%']);
            })
            ->orderByRaw(
                "ABS(latitude - $userLatitude) + ABS(longitude - $userLongitude)"
            )
            ->get();
        if (count($barbershops) > 0) {
            return response()->json([
                'status' => 200,
                'barbershops' => $barbershops,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found!',
            ], 404);
        }
    }

    public function getNearbyBarbershops(Request $request)
    {
        $userLongitude = $request->get('userLongitude');
        $userLatitude = $request->get('userLatitude');
        $barbershops = Barbershop::orderByRaw(
            "ABS(latitude - $userLatitude) + ABS(longitude - $userLongitude)"
        )
            ->limit(5)
            ->get();
        if (count($barbershops) > 0) {
            return response()->json([
                'status' => 200,
                'barbershops' => $barbershops,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'errors' => 'No barbershop found!',
            ], 404);
        }
    }

    public function getReservations(Request $request)
    {
        $barbershop_id = Barbershop::where('barbershop_owner_id', Auth::user()->id)->first()->id;
        

        $reservations = Reservation::where('barbershop_id', $barbershop_id)->orderBy('date', 'asc')->get();

        $data = [];

        foreach ($reservations as $reservation) {
            ReservationController::getStatus($reservation);
            $reservationId = $reservation->id;
            $services = Service::whereHas('reservations', function ($query) use ($reservationId) {
                $query->where('reservation_id', $reservationId);
            })->get();

            $servicedata = [];

            foreach ($services as $service) {
                $variations = Variation::where('service_id', $service->id)->get();
                $variationData = $variations->toArray();

                $serviceData = $service->toArray();
                $serviceData['variation'] = $variationData[0] ?? null;

                $servicedata[] = $serviceData;
            }
            $client = Client::where('id', $reservation->user_id)->first();

            $element = [
                'reservation' => $reservation,
                'services' => $servicedata,
                'client' => $client,
            ];

            $data[] = $element;
        }

        return response()->json($data);
    }
}