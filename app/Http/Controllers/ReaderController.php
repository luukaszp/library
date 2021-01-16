<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Reader;
use DB;

class ReaderController extends Controller
{
    /**
     * Display a listing of readers.
     * 
     */
    public function getReaders()
    {
        $readers = DB::table('readers')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->select(
                'users.id', 'users.name', 'users.surname', 'users.email', 'readers.card_number'
            )
            ->get()
            ->toArray();
        return $readers;
    }

    /**
     * Display specified reader.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showReader($id)
    {
        return User::find($id);
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

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;

        $reader = Reader::where('user_id', '=', $id);
        $reader->card_number = $request->card_number;

        $user->save();
        $reader->save();

        if ($reader->save()) {
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
        $reader = Reader::find($id);

        if (!$reader) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, reader with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($reader->destroy($id)) {
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
}
