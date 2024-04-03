<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;

// we will use laralvel sanctum for API authentication and will use token based authentication because wr are building API for mobile app
// register, login, profile, logout, refresh token | these are the functions we will implement in this controller

class AuthController extends Controller {
    /**
     * Registers a new user.
     */
    public function register(Request $request) {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        // If validation fails, return a 422 Unprocessable Entity response with validation errors
        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->all()
            ], 422);
        }

        // Get all the request data
        $data = $request->all();

        // Hash the user's password
        $data['password'] = bcrypt($data['password']);

        // Create a new user with the validated and hashed data
        $user = User::create($data);

        // Create a new access token for the user
        $accessToken = $user->createToken(
            'authToken',
            ["*"],
            now()->addMinutes(env('ACCESS_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        // Create a new refresh token for the user
        $refreshToken = $user->createToken(
            'refreshToken',
            ["*"],
            now()->addMinutes(env('REFRESH_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        // Set the refresh token to a cookie in the response
        $cookie = cookie('refreshToken', $refreshToken, env('REFRESH_TOKEN_EXPIRATION_TIME'));

        // Return a success response with the created user and access token, along with the refresh token cookie
        return response([
            'success' => true,
            'message' => 'User created successfully',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken
            ]
        ])->withCookie($cookie);
    }

    /**
     * Logs in a user using email and password credentials.
     */
    public function login(Request $request) {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        // If validation fails, return a 422 Unprocessable Entity response with validation errors
        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->all()
            ], 422);
        }

        // Get all the request data
        $data = $request->all();

        // Attempt to authenticate the user with the provided credentials
        if (!auth()->attempt($data)) {
            // If authentication fails, return a 401 Unauthorized response with an error message
            return response([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Get the authenticated user
        $user = auth()->user();

        // If user is not found, return a response (seems to be missing a response here in the original code)

        // Create a new access token for the user
        $accessToken = $user->createToken(
            'authToken',
            ["*"],
            now()->addMinutes(env('ACCESS_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        // Create a new refresh token for the user
        $refreshToken = $user->createToken(
            'refreshToken',
            ["*"],
            now()->addMinutes(env('REFRESH_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        // Set the refresh token to a cookie in the response
        $cookie = cookie('refreshToken', $refreshToken, env('REFRESH_TOKEN_EXPIRATION_TIME'));

        // Return a success response with the logged-in user and access token, along with the refresh token cookie
        return response([
            'success' => true,
            'message' => 'User logged in successfully',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken
            ]
        ])->withCookie($cookie);
    }

    /**
     * Refreshes the access token using a refresh token.
     */
    public function refresh(Request $request) {
        // Get the refresh token from the request cookie
        $refreshToken = $request->cookie('refreshToken');

        // If the refresh token is not found, return a 401 Unauthorized response
        if (!$refreshToken) {
            return response([
                'success' => false,
                'message' => 'Refresh token not found'
            ], 401);
        }

        // Find the personal access token using the refresh token
        $personalAccessTokenModel = Sanctum::$personalAccessTokenModel;
        $token = $personalAccessTokenModel::findToken($refreshToken);

        // If the token is not found or has expired, return a 401 Unauthorized response
        if (!$token || $token->expires_at->isPast()) {
            return response([
                'success' => false,
                'message' => 'Refresh token not found or expired'
            ], 401);
        }

        // Get the user associated with the token
        $user = $token->tokenable;

        // now delete the token
        $token->delete();

        // Create a new access token for the user
        $accessToken = $user->createToken(
            'authToken',
            ["*"],
            now()->addMinutes(env('ACCESS_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        // Create a new refresh token for the user
        $refreshToken = $user->createToken(
            'refreshToken',
            ["*"],
            now()->addMinutes(env('REFRESH_TOKEN_EXPIRATION_TIME'))
        )->plainTextToken;

        // Set the refresh token to a cookie in the response
        $cookie = cookie('refreshToken', $refreshToken, env('REFRESH_TOKEN_EXPIRATION_TIME'));

        // Return a success response with the user and access token, along with the refresh token cookie
        return response([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken
            ]
        ])->withCookie($cookie);
    }

    /**
     * Retrieves the profile of the authenticated user.
     */
    public function profile(Request $request) {
        // Return a JSON response with the user's profile data
        return response([
            'success' => true,
            'message' => 'User profile fetched successfully',
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    /**
     * Logs out the authenticated user by deleting the current access token and refresh token.
     */
    public function logout(Request $request) {
        // Delete the current access token of the authenticated user
        $request->user()->currentAccessToken()->delete();

        // Retrieve the current refresh token from the cookie
        $refreshToken = $request->cookie('refreshToken');

        // Find and delete the token matching the refresh token from the database
        $personalAccessTokenModel = Sanctum::$personalAccessTokenModel;
        $token = $personalAccessTokenModel::findToken($refreshToken);
        $token->delete();

        // Return a success response with a message and remove the refresh token cookie
        return response([
            'success' => true,
            'message' => 'User logged out successfully'
        ])->withCookie(cookie()->forget('refreshToken'));
    }
}
