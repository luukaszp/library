<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     /**
     * Display a listing of users.
     *
     */
    public function index()
    {
        $users = User::get(['id', 'name', 'surname', 'email', 'card_number'])->toArray();

        return $users;
    }

    /**
     * Display a listing of readers.
     *
     */
    public function getReaders()
    {
        $users = User::where('is_worker', 0);

        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, currently there are no readers.',
            ], 400);
        } else {
            return $users = User::where('is_worker', '=', 0)->get(['id', 'id_number', 'name', 'surname', 'email'])->toArray();
        }
    }

    /**
     * Display a listing of workers.
     *
     */
    public function getWorkers()
    {
        $users = User::where('is_worker', 1);

        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, currently there are no workers.',
            ], 400);
        } else {
            return $users = User::where('is_worker', '=', 1)->get(['id', 'id_number', 'name', 'surname', 'email'])->toArray();
        }
    }

    /**
     * Display specified user.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found.',
            ], 400);
        }

        return $user;
    }

    /**
     * Delete specified user (admin).
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found.',
            ], 400);
        }

        if ($user->delete()) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User could not be deleted.',
            ], 500);
        }
    }

    /**
     * Delete profile (user).
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($name)
    {
        $user = Auth::user();

        if (Auth::user()->name != $name) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, an error occurred.',
            ], 500);
        }

        if ($user->delete()) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User could not be deleted.',
            ], 500);
        }
    }

    /**
     * Changing password.
     * @param ChangePassword $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePassword $request)
    {
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json(['errors' => ['current' => ['Current password does not match']]], 422);
        }

        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            return response()->json(['errors' => [
                'current' => [
                    'New password cannot be same as your current password',
                ],
            ]], 422);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return response()->json([
            'message' => 'Your password has been updated successfully.',
        ]);
    }
}