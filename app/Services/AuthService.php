<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'status' => 422
            ], 422);
        }

        $emailDomain = substr($data['email'], strpos($data['email'], '@') + 1);
        $userType = ($emailDomain === 'goers.com') ? 'Admin' : 'Client';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $userType,
        ]);

        return [
            'success' => true,
            'data' => [
                'message' => 'User registered successfully',
                'user' => $user
            ],
            'status' => 201
        ];
    }

    public function login(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return [
                'success' => false,
                'data' => ['error' => 'Unauthorized'],
                'status' => 401
            ];
        }

        return [
            'success' => true,
            'data' => $this->respondWithToken($token),
            'message' => 'Login with ' . $credentials['email'] . ' success',
            'status' => 200
        ];
    }

    public function logout()
    {
        auth()->logout();
        return [
            'success' => true,
            'data' => ['message' => 'Successfully logged out'],
            'status' => 200
        ];
    }

    public function refresh()
    {
        return [
            'success' => true,
            'data' => $this->respondWithToken(auth()->refresh()),
            'status' => 200
        ];
    }

    public function user()
    {
        return [
            'success' => true,
            'data' => auth()->user(),
            'status' => 200
        ];
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
} 