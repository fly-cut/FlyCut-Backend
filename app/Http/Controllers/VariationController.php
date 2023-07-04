<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VariationController extends Controller
{
    public function index()
    {
        $variations = Variation::all();

        return response()->json(['data' => $variations]);
    }

    public function show($id)
    {
        $variation = Variation::find($id);
        if (!$variation) {
            return response()->json(['error' => 'Variation not found'], 404);
        }

        return response()->json(['data' => $variation]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'service_id' => 'required|integer|exists:services,id',
        ]);

        $path = $request->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path() . '/images';
        $path->move($destinationPath, $filename);

        $variation = Variation::create([
            'service_id' => $validatedData['service_id'],
            'name' => $validatedData['name'],
            'image' => $filename,

        ]);

        return response()->json(['variation' => $variation], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'service_id' => 'required|integer|exists:services,id',
        ]);

        $variation = Variation::find($id);
        if (!$variation) {
            return response()->json(['error' => 'Variation not found'], 404);
        }

        $image = $request->file('image');
        if ($image) {
            if (File::exists(public_path('images/' . $variation->image))) {
                File::delete(public_path('images/' . $variation->image));
                $path = $request->file('image');
                $filename = $path->getClientOriginalName();
                $destinationPath = public_path() . '/images';
                $path->move($destinationPath, $filename);
                $variation->image = $filename;
            }
        }

        $variation->name = $validatedData['name'];
        $variation->service_id = $validatedData['service_id'];
        $variation->save();

        return response()->json(['data' => $variation], 200);
    }

    public function destroy($id)
    {
        $variation = Variation::find($id);

        if (!$variation) {
            return response()->json(['error' => 'Variation not found'], 404);
        }
        $variation->delete();

        return response()->json(['message' => 'Variation deleted']);
    }
}
