<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Delete specified user (admin).
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found.',
                ], 400
            );
        }

        if ($user->delete()) {
            return response()->json(
                [
                'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'User could not be deleted.',
                ], 500
            );
        }
    }

    /**
     * Delete profile (user).
     *
     * @param  $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($name)
    {
        $user = Auth::user();

        if (Auth::user()->name != $name) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, an error occurred.',
                ], 500
            );
        }

        if ($user->delete()) {
            return response()->json(
                [
                'success' => true,
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'User could not be deleted.',
                ], 500
            );
        }
    }

    /**
     * Changing password.
     *
     * @param  ChangePassword $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePassword $request)
    {
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json(['errors' => ['current' => ['Current password does not match']]], 422);
        }

        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            return response()->json(
                ['errors' => [
                'current' => [
                    'New password cannot be same as your current password',
                ],
                ]], 422
            );
        }

        $user = Auth::user();
        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return response()->json(
            [
            'message' => 'Your password has been updated successfully.',
            ]
        );
    }

    /**
     * Store an avatar.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeAvatar(Request $request)
    {
        $user = User::find($request->get('user_id'));
        
        if ($file = $request->hasFile('avatar')) {
            $user->avatar = $imagePath = $request->file('avatar')->store('avatars', 'azure');
        }

        if ($user->save()) {
            return response()->json(
                [
                'success' => true,
                'user' => $user
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, avatar could not be added.',
                ], 500
            );
        }
    }
}
