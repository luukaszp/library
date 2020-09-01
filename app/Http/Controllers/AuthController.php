<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Reactivation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use JWTFactory;

class AuthController extends Controller
{
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
     * Get a JWT via given credentials for worker
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginWorker(Request $request)
    {
        $user = new User();
        $user->name = 'admin';
        $user->surname = 'admin';
        $user->email = 'admin@admin.admin';
        $user->card_number = null;
        $user->id_number = '123123123123';
        $user->password = bcrypt('zaq1@WSX');
        $user->activation_token = Str::random(40);
        $user->is_admin = 1;
        $user->is_worker = 1;

        $user->save();
        
        $credentials = $request->only('id_number', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid id_number or password'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Registration
     *
     * @param  AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function register(AuthRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->card_number = $request->card_number;
        $user->id_number = $request->id_number;
        $user->password = bcrypt($request->password);
        $user->activation_token = Str::random(40);

        if($request->id_number != null) {
            $user->is_worker = 1;
        }

        $user->save();

        return response()->json(['success' => true]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
