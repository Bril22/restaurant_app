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

    public function register(Request $request)
    {
        $result = $this->authService->register($request->all());
        return response()->json($result, $result['status']);
    }

    public function login(Request $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));
        return response()->json($result, $result['status']);
    }

    public function profile()
    {
        $result = $this->authService->user();
        return response()->json($result, $result['status']);
    }

    public function logout()
    {
        $result = $this->authService->logout();
        return response()->json($result, $result['status']);
    }
     
    public function refresh()
    {
        $result = $this->authService->refresh();
        return response()->json($result, $result['status']);
    }
}
