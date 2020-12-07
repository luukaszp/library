<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reader;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use JWTFactory;
use Config;

class LoginController extends Controller
{
    function __construct()
    {
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Reader::class,
        ]]);
    }

    /**
     * Get a JWT via given credentials for reader
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginReader(Request $request)
    {
        $credentials = $request->only('card_number', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid card_number or password'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(
            [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTFactory::getTTL() * 60,
            ], 201
        );
    }
}
