<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class BarbershopOwnerService
{
    public function changePassword($request)
    {
        $user = $request->user();
        $current_password = $request->current_password;
        $new_password = $request->new_password;
        if (!Hash::check($current_password, $user->password)) {
            $message = [
                'message' => 'Password isn\'t correct',
            ];

            return response($message, 422);
        }
        $user->update(['password' => Hash::make($new_password)]);
        $message = [
            'message' => 'Password changed successfully',
        ];

        return response($message, 200);
    }

    public function updateProfile($request)
    {
        $user = $request->user();
        $formData = $request->all();
        if ($request->file('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images/'), $image_name);
            $formData['image'] = $image_name;
        }
        $user->update($formData);
        $message = [
            'message' => 'Profile updated successfully',
            'barbershopOwner' => $user,
        ];

        return response($message, 200);
    }
    public function assignToken($request)
    {
        $owner = $request->user();
        $owner->token = $request->token;
        $owner->save();
        $message = [
            'message' => 'Token assigned successfully',
        ];
        return response($message, 200);
    }
}
