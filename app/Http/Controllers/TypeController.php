<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;

class TypeController extends Controller
{
    /**
     * Create a new type.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addType(Request $request)
    {
        $this->validate(
            $request, [
            'name' => 'required',
            ]
        );

        $type = new Type();
        $type->name = $request->name;

        $type->save();

        return response()->json(
            [
            'success' => true,
            'type' => $type
            ], 201
        );
    }

    /**
     * Edit specific type.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editType(Request $request, $id)
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, type with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $updated = $type->name = $request->name;
        $type->save();

        if ($updated) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Type has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, type could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified type.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteType($id)
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, type with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($type->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Type could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Display a listing of types.
     *
     * @return Response
     */
    public function getTypes()
    {
        $type = Type::get(['id', 'name'])->toArray();
        return $type;
    }
}