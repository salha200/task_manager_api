<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return JsonResponse
     */

    public function register(array $data): JsonResponse
    {
        $user = User::create($data);

        if($user){
            $token = Auth::login($user);
            return $this->responseWithToken($token, $user);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occur while trying to create user',
            ], 500);
        }
    }

    /**
     * Authenticate a user and return a token.
     *
     * @param array $Data
     * @return JsonResponse
     */
    public function login(array $Data): JsonResponse
    {
        $token = Auth::attempt($Data);

        if ($token) {
            return $this->responseWithToken($token, Auth::user());
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Invalid credentials',
        ], 403);
    }

    /**
     * Refresh the authentication token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $newToken = Auth::refresh();
            return $this->responseWithToken($newToken, Auth::user());
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Token has expired',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Could not refresh the token',
            ], 500);
        }
    }

    /**
     * Log the user out.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'User has been logged out',
        ]);
    }

    /**
     * Generate a response with the authentication token and user details.
     *
     * @param $token
     * @param $user
     * @return JsonResponse
     */
    protected function responseWithToken($token, $user): JsonResponse
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60,
            'token_type' => 'bearer',
        ]);
    }
}
