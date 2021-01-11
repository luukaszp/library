<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use App\Reader;
use DB;
use Auth;

class FollowController extends Controller
{
    /**
     * Add author to the list of followed authors.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addAuthor(Request $request)
    {
        $readerID = Reader::where('user_id', '=', $request->user_id)->get()->first();

        $author = Author::find($request->author_id);

        $author->readers()->attach($readerID);

        return response()->json(
            [
            'success' => true,
            'message' => 'Author has been followed.'
            ], 201
        );
    }

    /**
     * Remove the specified author from the list of followers.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeAuthor($id)
    {
        if (Reader::where('user_id', '=', $id)->authors()->detach()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Author was deleted from followers.'
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Author could not be removed from the list.'
                ], 500
            );
        }
    }

    /**
     * Display the list of followed authors for a specific reader.
     *
     * @return Response
     */
    public function getFollowedAuthors($id)
    {
        $readerID = Reader::where('user_id', '=', $id)->get('id')->first();
        $followed = Reader::with(['authors:name,surname,id'])->where('user_id', '=', $id)->get()->toArray();

        if (!$followed) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Reader does not follow any authors.'
                ], 400
            );
        }

        return $followed = Reader::find($readerID->id)->authors()->select('id', 'name', 'surname')->first();
    }
}
