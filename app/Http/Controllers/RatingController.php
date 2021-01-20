<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Reader;
use App\Book;
use DB;
use Carbon\Carbon;

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
            ->join('readers', 'readers.id', '=', 'ratings.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->select(
                'ratings.id', 'ratings.rate', 'ratings.created_at', 'ratings.opinion', 'users.name', 
                'users.surname', 'users.avatar', 'users.id as user_id'
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
        $rating->opinion = $request->opinion;
        $rating->book_id = $request->book_id;
        $rating->reader_id = auth()->user()->id;

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

    /**
     * Get amount of ratings for all months.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ratingsAmount($id)
    {
        $reader = Reader::where('user_id', '=', $id)->pluck('id');
        $rating = Rating::where('reader_id', '=', $reader[0])
            ->select('rate', 'updated_at')
            ->get()
            ->groupBy(
                function ($date) {
                    return Carbon::parse($date->updated_at)->format('m'); // grouping by months
                }
            );
            
        if (!$rating) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, reader has no ratings.'
                ], 400
            );
        }

        $ratingsCount = [];
        $amount = [];
        
        foreach ($rating as $key => $value) {
            $ratingsCount[(int)$key] = count($value);
        }
        
        for($i = 1; $i <= 12; $i++){
            if(!empty($ratingsCount[$i])) {
                $amount[$i] = $ratingsCount[$i];    
            }else{
                $amount[$i] = 0;    
            }
        }

        return $amount;
    }

    /**
     * Edit specific rating.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editRating(Request $request, $id)
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

        $reader_id = Reader::where('id', auth()->user()->id)->get('id')->first();

        $rating->rate = $request->rate;
        $rating->opinion = $request->opinion;
        $rating->reader_id = $reader_id->id;
        $rating->book_id = $request->book_id;

        $rating->save();

        if ($rating->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Rating has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, rating could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Get amount of ratings for current month
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getMonthlyRating()
    {
        $firstDay = Carbon::now()->startOfMonth()->toDateString(); //current month
        $lastDay = Carbon::now()->endOfMonth()->toDateString(); 

        $currentMonth = DB::table('ratings')
            ->whereBetween('ratings.created_at', [$firstDay, $lastDay])
            ->join('readers', 'readers.id', '=', 'ratings.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->select(
                'users.name', 'users.surname', 'users.avatar', DB::raw('COUNT(ratings.created_at) as count')
            )
            ->groupBy('users.id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return $currentMonth;
    }
}
