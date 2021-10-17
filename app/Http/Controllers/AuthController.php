<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\Reactivation;
use App\User;
use App\Worker;
use App\Reader;
use App\Mail\NewUserMail;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use JWTFactory;
use Carbon\Carbon;
use Config;

class AuthController extends Controller
{
    function __construct()
    {
        Config::set(
            'auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Worker::class,
            ]]
        );
    }

    /**
     * Get a JWT via given credentials for worker
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginWorker(Request $request)
    {
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
        $user->activation_token = Str::random(40);

        $user->save();

        if($request->id_number != null) {
            $worker = new Worker();
            $worker->id_number = $request->id_number;
            $worker->password = bcrypt($request->password);
            $worker->user_id = $user->id;
            $worker->save();
        }

        if($request->card_number != null) {
            $reader = new Reader();
            $reader->card_number = $request->card_number;
            $reader->password = bcrypt($request->password);
            $reader->user_id = $user->id;
            $reader->save();
        }

        $details = [
            'title' => 'Zmiana hasła',
            'url' => "https://library-site.herokuapp.com/first-login",
            'name' => $user->name
        ];

        if($request->id_number === null) {
            Mail::to($user->email)->send(new NewUserMail($details));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Password change
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function passwordChange(Request $request)
    {
        $reader = Reader::where('user_id', '=', $request->user_id)->first();

        if (!$reader) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, something went wrong.'
                ], 400
            );
        }

        $reader->password = bcrypt($request->password);
        $reader->save();

        return response()->json(['success' => true]);
    }

    /**
     * Password change during first login
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function firstLoginPassword(Request $request)
    {
        $reader = Reader::where('card_number', '=', $request->card_number)->first();

        if (!$reader) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Wrong card number given.'
                ], 401
            );
        }

        $user = User::where('email', '=', $request->email)->first();

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Wrong email.'
                ], 400
            );
        }

        if ($user->email_verified_at !== null) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, your email is already verified.'
                ], 400
            );
        }

        $reader->password = bcrypt($request->password);
        $user->email_verified_at = Carbon::now()->toDateTimeString();

        $user->save();
        $reader->save();

        return response()->json(['success' => true]);
    }

    /**
     * Password reset
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function passwordReset(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, user with provided email cannot be found.'
                ], 400
            );
        }

        if ($user->email_verified_at === null) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, you need to verify your email first.'
                ], 400
            );
        }

        $details = [
            'title' => 'Resetowanie hasła',
            'url' => "https://library-site.herokuapp.com/new-password/$user->id",
            'name' => $user->name
        ];

        Mail::to($user->email)->send(new ResetPasswordMail($details));

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
