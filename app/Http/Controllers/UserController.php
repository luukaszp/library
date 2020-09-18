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
      * Display a listing of users with roles.
      */
    public function getRoles()
    {
        $users = User::get(['id', 'name', 'surname', 'email', 'is_worker', 'is_admin'])->toArray();

        return $users;
    }

    /**
     * Edit roles for specific user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editRoles(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $user->is_worker = $request->is_worker;
        $user->is_admin = $request->is_admin;

        if ($request->is_worker == 0 && $request->is_admin == 0) {
            $user->id_number = null;
        }
        
        $user->save();

        if ($user->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'User has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, user could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Display a listing of readers.
     */
    public function getReaders()
    {
        $users = User::where('is_worker', 0);

        if (!$users) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, currently there are no readers.',
                ], 400
            );
        } else {
            return $users = User::where('is_worker', '=', 0)->where('is_admin', '=', 0)->get(['id', 'card_number', 'name', 'surname', 'email'])->toArray();
        }
    }

    /**
     * Display a listing of workers.
     */
    public function getWorkers()
    {
        $users = User::where('card_number', null);

        if (!$users) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, currently there are no workers.',
                ], 400
            );
        } else {
            return $users = User::where('card_number', '=', null)->get(['id', 'id_number', 'name', 'surname', 'email'])->toArray();
        }
    }

    /**
     * Display specified user.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showReader($id)
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

        return $user;
    }

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
     * Edit specific reader.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editReader(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, reader with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $user->card_number = $request->card_number;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->save();

        if ($user->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Reader has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, reader could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified reader.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReader($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, reader with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($user->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Reader could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Edit specific worker.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editWorker(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, worker with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $user->id_number = $request->id_number;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->save();

        if ($user->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Worker has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, worker could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified worker.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteWorker($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, worker with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($user->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Worker could not be deleted.'
                ], 500
            );
        }
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
            $user->avatar = $imagePath = $request->file('avatar')->store('avatars', 'public');

            $avatar = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $avatar->save();

            $imageArray = ['avatar' => $imagePath];
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
