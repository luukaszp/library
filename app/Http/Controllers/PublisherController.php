<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Publisher;

class PublisherController extends Controller
{
    // pokazac info + autorow + ksiazki?
    /**
     * Create a new publisher.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addPublisher(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $publisher = new Publisher();
        $publisher->name = $request->name;

        $publisher->save();

        return response()->json([
            'success' => true,
            'publisher' => $publisher
        ], 201);
    }

    /**
     * Edit specific publisher.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editPublisher(Request $request, $id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, publisher with id ' . $id . ' cannot be found.'
            ], 400);
        }

        $updated = $publisher->name = $request->name;
        $publisher->save();

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Publisher has been updated',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, publisher could not be updated.',
            ], 500);
        }
    }

    /**
     * Remove the specified category.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePublisher($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, publisher with id ' . $id . ' cannot be found.'
            ], 400);
        }

        if ($publisher->destroy($id)) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Publisher could not be deleted.'
            ], 500);
        }
    }

    /**
     * Display a listing of publishers.
     *
     * @return Response
     */
    public function getPublishers()
    {
        $publisher = Publisher::get(['id', 'name'])->toArray();
        return $publisher;
    }
}
