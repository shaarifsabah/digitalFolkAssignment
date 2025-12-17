<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->sendResponse(true, ['access_token' => $token, 'token_type' => 'Bearer'], 'Login successful');
        }

        return $this->sendResponse(false, message: 'Invalid credentials', statusCode: 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUser(Request $request)
    {
        return $this->sendResponse(true, $request->user(), 'User retrieved successfully');
    }

    /**
     * Logout the user and revoke current token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse(true, message: 'Logged out successfully');
    }
}
