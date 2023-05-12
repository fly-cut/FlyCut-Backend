<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admins/register",
     *     summary="Register a new admin",
     *     description="Create a new admin account with the provided details.",
     *     operationId="adminAuthRegister",
     *     tags={"Admin_Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Provide the admin details",
     *
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="SecretPassword"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="SecretPassword"),
     *         ),
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Admin account created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="admin", type="object"),
     *             @OA\Property(property="token", type="string", example="xxxxxxxxxxxxxxx"),
     *             @OA\Property(property="message", type="string", example="Admin registered successfully"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 ),
     *
     *                 @OA\Property(property="email", type="array",
     *
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 ),
     *
     *                 @OA\Property(property="password", type="array",
     *
     *                     @OA\Items(type="string", example="The password field is required.")
     *                 ),
     *
     *                 @OA\Property(property="password_confirmation", type="array",
     *
     *                     @OA\Items(type="string", example="The password confirmation field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:admins,email|email|max:255',
            'password' => 'required|string|confirmed|max:255',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $token = $admin->createToken('AdminToken')->plainTextToken;
        $response = [
            'admin' => $admin,
            'token' => $token,
            'message' => 'Admin registered successfully',
        ];

        return response($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/admins/login",
     *     summary="Admin Login",
     *     description="Authenticate an admin user by email and password.",
     *     operationId="adminAuthLogin",
     *     tags={"Admin_Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credentials for authentication",
     *
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="admin@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="password123"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success response with admin and token information.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="admin"
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized response with error message.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="response",
     *                 type="string",
     *                 example="Please enter the right email or password!"
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response([
                'response' => 'Please enter the right email or password!',
            ], 401);
        }

        $token = $admin->createToken('AdminToken')->plainTextToken;

        $response = [
            'admin' => $admin,
            'token' => $token,
        ];

        return response($response, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/admins/logout",
     *     summary="Logout a user",
     *     description="Logout a user by revoking their token",
     *     operationId="adminAuthLogout",
     *     tags={"Admin_Auth"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="response",
     *                 type="string",
     *                 example="Logged out"
     *             )
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'response' => 'Logged out',
        ];
    }
}
