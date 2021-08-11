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

    // CREATE ADMIN REGISTER API
    public function register(RegisterRequest $request){
        // SAVE ADMIN
        $admin = new User();

        $admin->create(
            $request->only('first_name','last_name','email') +
            [
                'password' => bcrypt($request->input('password'))
            ]
            );
        return response($admin,Response::HTTP_CREATED);
    }

    // CREATE ADMIN LOGIN API
    public function login(LoginRequest $request){

        $credentials = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        if($credentials){
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
        ],Response::HTTP_UNAUTHORIZED);
    }

}
