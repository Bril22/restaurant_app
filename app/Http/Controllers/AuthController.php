<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Register a new user.
    public function register(Request $request)
    {
        $result = $this->authService->register($request->all());
        return response()->json($result, $result['status']);
    }

    // Login user and create token.
    public function login(Request $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));
        return response()->json($result, $result['status']);
    }

    // Get the authenticated User.
    public function me()
    {
        $result = $this->authService->me();
        return response()->json($result, $result['status']);
    }

    // Logout the user (Invalidate the token).
    public function logout()
    {
        $result = $this->authService->logout();
        return response()->json($result, $result['status']);
    }
     
    // Refresh a token
    public function refresh()
    {
        $result = $this->authService->refresh();
        return response()->json($result, $result['status']);
    }
}
