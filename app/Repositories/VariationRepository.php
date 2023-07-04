<?php

namespace App\Repositories;

use App\Models\Variation;
use Illuminate\Support\Facades\File;

class VariationRepository
{
    public function getAll()
    {
        return Variation::all();
    }

    public function getById($id)
    {
        return Variation::find($id);
    }

    public function create($request)
    {
        $path = $request->file('image');
        $filename = $path->getClientOriginalName();
        $destinationPath = public_path().'/images';
        $path->move($destinationPath, $filename);

        $variation = Variation::create([
            'service_id' => $request['service_id'],
            'name' => $request['name'],
            'image' => $filename,

        ]);
        return $variation;
    }

    public function update($request, $id)
    {
        $variation = $this->getById($id);
        $image = $request->file('image');
        if ($image) {
            if (File::exists(public_path('images/'.$variation->image))) {
                File::delete(public_path('images/'.$variation->image));
                $path = $request->file('image');
                $filename = $path->getClientOriginalName();
                $destinationPath = public_path().'/images';
                $path->move($destinationPath, $filename);
                $variation->image = $filename;
            }
        }

        $variation->name = $request['name'];
        $variation->service_id = $request['service_id'];
        $variation->save();
        return $variation;
    }

    public function delete($id)
    {
        $variation = Variation::find($id);
        if (File::exists(public_path('images/'.$variation->image))) {
            File::delete(public_path('images/'.$variation->image));
        }
        Variation::destroy($id);
    }

}
