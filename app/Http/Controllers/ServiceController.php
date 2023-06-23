<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::all();

        return response()->json($services);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'required||image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $path = $request->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path().'/images';
        $path->move($destinationPath, $filename);

        $service = Service::create([
            'name' => $validatedData['name'],
            'image' => $filename,
        ]);

        return response()->json($service, 201);
    }

    public function show($id): JsonResponse
    {
        $service = Service::find($id);
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        return response()->json($service);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $service = Service::find($id);
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        if ($image) {
            if (File::exists(public_path('images/'.$service->image))) {
                File::delete(public_path('images/'.$service->image));
                $path = $request->file('image');
                $filename = $path->getClientOriginalName();
                $destinationPath = public_path().'/images';
                $path->move($destinationPath, $filename);
                $service->image = $filename;
            }
        }

        $service->name = $validatedData['name'];
        $service->save();

        return response()->json($service);
    }

    public function updateListOfServices(Request $request)
    {
        $services = $request->input('services');
        $user_id = Auth::guard('barbershopOwner-api')->user()->id;
        $barbershop = Barbershop::where('barbershop_owner_id', $user_id)->first();

        foreach ($services as $servicee) {
            $service = Service::find($servicee['id']);

            $barbershop->services()->syncWithoutDetaching([$service->id => [
                'slots' => $servicee['slots'],
                'price' => $servicee['price'],
            ]]);
            $barbershop->save();
        }
    }

    public function destroy($id): JsonResponse
    {
        $service = Service::find($id);
        if (File::exists(public_path('images/'.$service->image))) {
            File::delete(public_path('images/'.$service->image));
        }
        if (! $service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $service->delete();

        return response()->json(['message' => 'Service deleted']);
    }

    /**
     * Get variations of a particular service
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function getServiceVariations($service_id)
    {
        $service = Service::find($service_id);
        if (! $service) {
            return response()->json(['error' => 'There is no such service exists!'], 404);
        }
        $variations = $service->variations;

        return response()->json(['variations' => $variations], 200);
    }
}
