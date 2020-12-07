<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Worker;
use DB;

class RoleController extends Controller
{
    /**
     * Display a listing of workers with roles.
     */
    public function getRoles()
    {
        $roles = DB::table('workers')
            ->join('users', 'users.id', '=', 'workers.user_id')
            ->select(
                'users.id', 'users.name', 'users.surname', 'users.email', 'workers.is_admin'
            )
            ->get()
            ->toArray();

        return $roles;
    }

    /**
     * Edit roles for specific worker.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editRoles(Request $request, $id)
    {
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, worker with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $worker->is_admin = $request->is_admin;
        
        $worker->save();

        if ($worker->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Worker data has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, worker data could not be updated.',
                ], 500
            );
        }
    }
}
