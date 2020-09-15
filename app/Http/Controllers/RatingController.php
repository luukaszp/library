<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Book;
use DB;

class RatingController extends Controller
{
    /**
     * Display ratings for specified book
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showRating($id)
    {
        $rates = Rating::where('book_id', $id);
        $average = (float) $rates->avg('rate');
        $round = round($average, 1);
        $data = [
            'avg' => $round,
            'count' => $rates->count('rate')
        ];

        return response()->json(
            [
            'success' => true,
            'ratings' => $data,
            ]
        );
    }

    /**
     * Display all ratings for specified book
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRatings($id)
    {
        $rating = DB::table('ratings')
            ->where('ratings.book_id', '=', $id)
            ->join('users', 'users.id', '=', 'ratings.user_id')
            ->select(
                'ratings.rate', 'ratings.created_at', 'users.name', 'users.surname', 'users.id'
            )
            ->get()
            ->toArray();

        return $rating;
    }

    /**
     * Store a newly created rating.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addRating(Request $request)
    {
        $this->validate(
            $request, [
            'rate' => 'required',
            ]
        );

        $book = Book::find($request->id);

        $rating = new Rating();
        $rating->rate = $request->rate;
        $rating->user_id = auth()->user()->id;
        $rating->book_id = $request->book_id;

        if ($rating->save()) {
            return response()->json(
                [
                'success' => true,
                'rating' => $rating,
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, this book could not be rated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified rating.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRating($id)
    {
        $rating = Rating::find($id);

        if (!$rating) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, rating with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($rating->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Rating could not be deleted.'
                ], 500
            );
        }
    }
}
