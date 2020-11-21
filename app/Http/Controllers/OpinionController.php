<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Opinion;
use App\Rating;
use App\Book;
use DB;

class OpinionController extends Controller
{
     /**
      * Display all opinions for specified book
      *
      * @param  $id
      * @return \Illuminate\Http\JsonResponse
      */
    public function getOpinions($id)
    {
        $opinion = DB::table('opinions')
            ->where('opinions.book_id', '=', $id)
            ->select(
                'opinions.opinion', 'opinions.id'
            )
            ->get()
            ->toArray();

        return $opinion;
    }

    /**
     * Store a newly created opinion.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addOpinion(Request $request)
    {
        $book = Book::find($request->id);

        $opinion = new Opinion();
        $opinion->opinion = $request->opinion;
        $opinion->user_id = auth()->user()->id;
        $opinion->book_id = $request->book_id;
        $opinion->rating_id = Rating::latest()->first()->id;

        if ($opinion->save()) {
            return response()->json(
                [
                'success' => true,
                'opinion' => $opinion,
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, opinion could not be added to this book.',
                ], 500
            );
        }
    }

    
    /**
     * Edit specific opinion.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editOpinion(Request $request, $id)
    {
        $opinion = Opinion::find($id);

        if (!$opinion) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, opinion with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $opinion->opinion = $request->opinion;
        $opinion->user_id = auth()->user()->id;
        $opinion->book_id = $request->book_id;
        //$opinion->rating_id = Rating::latest()->first()->id;

        $opinion->save();

        if ($opinion->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Opinion has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, opinion could not be updated.',
                ], 500
            );
        }
    }
}
