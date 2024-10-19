<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;

        $this->middleware('auth:api', [
            'except' => ['register', 'login']
        ]);
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request):JsonResponse
    {
        return $this->authService->register($request->validated());
    }

    /**
     * Handle user login and return a JWT token.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->login($request->validated());
    }

    /**
     * Logout the authenticated user and invalidate the JWT token.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->authService->logout();
    }

    /**
     * Refresh the JWT token for the authenticated user.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->authService->refresh();
    }

    /**
     * Get the current authenticated user.
     *
     * @return JsonResponse
     */
    public function current(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}
