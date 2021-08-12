<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *   path="/register",
     *      tags={"Register"},
     *      summary="Register",
     *      operationId="register",
     *
     *      @OA\Parameter(
     *          name="first_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="last_name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success Registration",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     *)
     **/

    // CREATE ADMIN REGISTER API
    public function register(RegisterRequest $request)
    {
        // SAVE ADMIN
        $admin = new User();
        $admin->create(
            $request->only('first_name', 'last_name', 'email') +
                [
                    'password' => bcrypt($request->input('password')),
                    'role_id' => 1
                ]
        );
        return response()->json([
            'success' => 'user created successfully'
        ],Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *   path="/login",
     *      tags={"Login"},
     *      summary="Login",
     *      operationId="login",
     *
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     *)
     **/


    // CREATE ADMIN LOGIN API
    public function login(LoginRequest $request)
    {

        $credentials = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        // return $credentials;
        if ($credentials) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;

            return response()->json([
                'status' => 1,
                'message' => 'User logged successfully',
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Invalide creadentials'
        ], Response::HTTP_UNAUTHORIZED);
    }
}
