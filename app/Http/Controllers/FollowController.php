<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use App\User;
use DB;
use Auth;

class FollowController extends Controller
{
    /**
     * Add author to the list of following authors.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addAuthor(Request $request)
    {
        $author = Author::find($request->author_id);

        $author->users()->attach($author);
        
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
        if (User::find($id)->authors()->detach()) {
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
     * Display the list of favourite authors for a specific user.
     *
     * @return Response
     */
    public function getFollowedAuthors($id)
    {
        return $followed = User::find($id)->authors;
    }
}
