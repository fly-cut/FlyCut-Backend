<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Mail\VerifyEmail;
use App\Models\Barbershop;
use App\Models\BarbershopOwner;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BarbershopOwnerAuthController extends Controller
{
    /**
     * Register a new barbershop owner.
     *
     * @OA\Post(
     * path="/api/barbershopOwners/register",
     * summary="Register a new barbershop owner",
     * description="Creates a new barbershop owner and sends an email to verify the email address",
     * tags={"BarbershopOwner_Auth"},
     *
     * @OA\RequestBody(
     * required=true,
     * description="Provide barbershop owner credentials",
     *
     * @OA\JsonContent(
     * required={"name","email","password","password_confirmation"},
     *
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     * @OA\Property(property="password", type="string", format="password", example="password"),
     * @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     * ),
     * ),
     *
     * @OA\Response(
     * response=201,
     * description="Barbershop owner registered successfully",
     *
     * @OA\JsonContent(
     *
     * @OA\Property(property="barbershopOwner", type="object",
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     * @OA\Property(property="password", type="string"),
     * @OA\Property(property="created_at", type="string"),
     * @OA\Property(property="updated_at", type="string"),
     * @OA\Property(property="email_verified_at", type="string", format="date-time"),
     * @OA\Property(property="id", type="integer", format="int32"),
     * ),
     * @OA\Property(property="token", type="string"),
     * @OA\Property(property="message", type="string", example="Barbershop owner registered successfully"),
     * ),
     * ),
     *
     * @OA\Response(
     * response=422,
     * description="Invalid input data",
     *
     * @OA\JsonContent(
     *
     * @OA\Property(property="message", type="object",
     * @OA\Property(property="name", type="array", @OA\Items(type="string")),
     * @OA\Property(property="email", type="array", @OA\Items(type="string")),
     * @OA\Property(property="password", type="array", @OA\Items(type="string")),
     * ),
     * ),
     * ),
     *
     * @OA\Response(
     * response=500,
     * description="Internal server error",
     * )
     * )
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|unique:barbershop_owners,email|email|max:255',
            'password' => 'required|string|confirmed|max:255',
        ]);

        $barbershop_owner = BarbershopOwner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $token = $barbershop_owner->guard(['barbershopOwner-api'])->createToken('BarbershopOwnerAccessToken')->accessToken;
        $response = [
            'barbershopOwner' => $barbershop_owner,
            'token' => $token,
            'message' => 'Barbershop owner registered successfully',
        ];

        return response($response, 201);
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['message' => $validator->errors()]);
        }
        $select = DB::table('password_reset_tokens')
            ->where('email', Auth::guard('barbershopOwner-api')->user()->email)
            ->where('token', $request->token);

        if ($select->get()->isEmpty()) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid PIN'], 400);
        }

        $select = DB::table('password_reset_tokens')
            ->where('email', Auth::guard('barbershopOwner-api')->user()->email)
            ->where('token', $request->token)
            ->delete();

        $barbershop_owner = BarbershopOwner::find(Auth::user()->id);
        $barbershop_owner->email_verified_at = Carbon::now()->toDateTimeString();
        $barbershop_owner->save();

        return new JsonResponse(['success' => true, 'message' => 'Email is verified'], 200);
    }

    public function resendPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $verify = DB::table('password_reset_tokens')->where([
            ['email', $request->all()['email']],
        ]);

        if ($verify->exists()) {
            $verify->delete();
        }

        $token = random_int(100000, 999999);
        $password_reset = DB::table('password_reset_tokens')->insert([
            'email' => $request->all()['email'],
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        if ($password_reset) {
            Mail::to($request->all()['email'])->send(new VerifyEmail($token));

            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'A verification mail has been resent',
                ],
                200
            );
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $barbershop_owner = BarbershopOwner::where('email', $request->email)->first();
        if (!$barbershop_owner || !Hash::check($request->password, $barbershop_owner->password)) {
            return response(
                [
                    'Response' => 'Please enter the right email or password!',
                ],
                401
            );
        }
        $token = $barbershop_owner->guard(['barbershopOwner-api'])->createToken('BarbershopOwnerAccessToken')->accessToken;

        $response = [
            'barbershopOwner' => $barbershop_owner,
            'token' => $token,
        ];

        return response($response, 200);
    }

    public function logout()
    {
        Auth::user()->token()->revoke();

        return [
            'response' => 'Logged out',
        ];
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $verify = BarbershopOwner::where('email', $request->all()['email'])->exists();

        if ($verify) {
            $verify2 = DB::table('password_reset_tokens')->where([
                ['email', $request->all()['email']],
            ]);

            if ($verify2->exists()) {
                $verify2->delete();
            }

            $token = random_int(100000, 999999);
            $password_reset = DB::table('password_reset_tokens')->insert([
                'email' => $request->all()['email'],
                'token' => $token,
                'created_at' => Carbon::now()->toDateTimeString(),
            ]);

            if ($password_reset) {
                Mail::to($request->all()['email'])->send(new ResetPassword($token));

                return new JsonResponse(
                    [
                        'success' => true,
                        'message' => 'Please check your email for a 6 digit pin',
                    ],
                    200
                );
            }
        } else {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'This email does not exist',
                ],
                400
            );
        }
    }

    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $check = DB::table('password_reset_tokens')->where([
            ['email', $request->all()['email']],
            ['token', $request->all()['token']],
        ]);

        if ($check->exists()) {
            $difference = Carbon::now()->diffInSeconds($check->first()->created_at);
            if ($difference > 3600) {
                return new JsonResponse(['success' => false, 'message' => 'Token Expired'], 400);
            }

            $delete = DB::table('password_reset_tokens')->where([
                ['email', $request->all()['email']],
                ['token', $request->all()['token']],
            ])->delete();

            return new JsonResponse(
                [
                    'success' => true,
                    'message' => 'You can now reset your password',
                ],
                200
            );
        } else {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Invalid token',
                ],
                401
            );
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
        }

        $barbershop_owner = BarbershopOwner::where('email', $request->email);
        $barbershop_owner->update([
            'password' => Hash::make($request->password),
        ]);

        $token = $barbershop_owner->first()->guard(['barbershopOwner-api'])->createToken('BarbershopOwnerAccessToken')->accessToken;

        return new JsonResponse(
            [
                'success' => true,
                'message' => 'Your password has been reset',
                'token' => $token,
            ],
            200
        );
    }

    //write a function to get the barbershop of a barbershop owner
    public function getBarbershopOfBarbershopOwner()
    {
        $barbershop = Barbershop::where('barbershop_owner_id', Auth::user()->id)->first();
        if (!$barbershop) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'You do not have a barbershop',
                ],
                400
            );
        }

        return new JsonResponse(
            [
                'success' => true,
                'barbershop' => $barbershop,
            ],
            200
        );
    }
}
