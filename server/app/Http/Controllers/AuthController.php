<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

// we will use laralvel sanctum for API authentication and will use token based authentication because wr are building API for mobile app
// register, login, profile, logout, refresh token | these are the functions we will implement in this controller

class AuthController extends Controller {
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->all()
            ], 422);
        }

        $data = $request->all();

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        // create access token
        $accessToken = $user->createToken(
            'authToken',
            ["*"],
            now()->addMinutes(env('ACCESS_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;


        // create refresh token
        $refreshToken = $user->createToken(
            'refreshToken',
            ["*"],
            now()->addMinutes(env('REFRESH_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        // dd($accessToken, $refreshToken);
        // set token expiration time to the token
        $cookie = cookie('refreshToken', $refreshToken, env('REFRESH_TOKEN_EXPIRATION_TIME'));

        return response([
            'success' => true,
            'message' => 'User created successfully',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken
            ]
        ])->withCookie($cookie);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->all()
            ], 422);
        }

        $data = $request->all();

        if (!auth()->attempt($data)) {
            return response([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth()->user();

        if (!$user) {
            return;
        }

        // create access token
        $accessToken = $user->createToken(
            'authToken',
            ["*"],
            now()->addMinutes(env('ACCESS_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;


        // create refresh token
        $refreshToken = $user->createToken(
            'refreshToken',
            ["*"],
            now()->addMinutes(env('REFRESH_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;
        // set token expiration time to the token
        $cookie = cookie('refreshToken', $refreshToken, env('REFRESH_TOKEN_EXPIRATION_TIME'));

        return response([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken
            ]
        ])->withCookie($cookie);
    }

    public function refresh(Request $request) {
        $refreshToken = $request->cookie('refreshToken');

        if (!$refreshToken) {
            return response([
                'success' => false,
                'message' => 'Refresh token not found'
            ], 401);
        }

        $user = User::find(Redis::get($refreshToken));

        if (!$user) {
            return response([
                'success' => false,
                'message' => 'User not found'
            ], 401);
        }

        // create access token
        $accessToken = $user->createToken(
            'authToken',
            ["*"],
            now()->addMinutes(env('ACCESS_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        return response([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken
            ]
        ]);
    }

    public function profile(Request $request) {
        // dd('here');
        return response([
            'success' => true,
            'message' => 'User profile fetched successfully',
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response([
            'success' => true,
            'message' => 'User logged out successfully'
        ])->withCookie(cookie()->forget('refreshToken'));
    }
}
