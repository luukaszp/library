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
        $author = Author::find($request->author_id);

        $author->readers()->attach($author);

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
        if (Reader::find($id)->authors()->detach()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Author was deleted from followers.'
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => true,
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
        return $followed = Reader::find($id)->authors;
    }
}
