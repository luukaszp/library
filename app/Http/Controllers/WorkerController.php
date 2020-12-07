<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Worker;
use DB;

class WorkerController extends Controller
{
    /**
     * Display a listing of workers.
     */
    public function getWorkers()
    {
        $workers = DB::table('workers')
            ->join('users', 'users.id', '=', 'workers.user_id')
            ->select(
                'users.id', 'users.name', 'users.surname', 'users.email', 'workers.id_number'
            )
            ->get()
            ->toArray();
        return $workers;
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

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;

        $worker = Worker::where('user_id', '=', $id);
        $worker->id_number = $request->id_number;

        $user->save();
        $worker->save();

        if ($worker->save()) {
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
        $worker = Worker::find($id);

        if (!$worker) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, worker with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($worker->destroy($id)) {
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
}
