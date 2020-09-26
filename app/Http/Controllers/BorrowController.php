<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Borrow;
use App\Book;
use DB;
use Carbon\Carbon;

class BorrowController extends Controller
{
    /**
     * Create a new book borrowing.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addBorrow(Request $request)
    {
        $book = $request->book_id;

        for($index = 0; $index < sizeof($request->book_id); $index++) {
            $borrow = new Borrow();
            $borrow->user_id = $request->user_id;
            $borrow->borrows_date = $request->borrows_date;

            $idOfBook = $request->book_id[$index];
            $stringToInt = (int)$idOfBook;
            $borrow->book_id = $stringToInt;

            $date = Carbon::createFromFormat('Y-m-d', $request->borrows_date);
            $daysToAdd = 30;
            $date = $date->addDays($daysToAdd);
            $borrow->returns_date = $date->toDateString();
            $borrow->save();

            $book = Book::find($stringToInt);
            $book->amount = $book->amount-1;
            $book->save();
        }

        if ($borrow->save()) {
            return response()->json(
                [
                'success' => true,
                'borrow' => $borrow
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing could not be added.',
                ], 500
            );
        }
    }

    /**
     * Display a listing of current borrowings.
     *
     * @return Response
     */
    public function getBorrows()
    {
        $data = DB::table('borrows')
            ->where('borrows.is_returned', '=', '0')
            ->join('users', 'users.id', '=', 'borrows.user_id')
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'users.name', 'users.surname', 'borrows.borrows_date', 'borrows.returns_date',
                'borrows.id', 'books.id as bookID'
            )
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * Display a history of borrowings.
     *
     * @return Response
     */
    public function getPastBorrows()
    {
        $data = DB::table('borrows')
            ->where('borrows.is_returned', '=', '1')
            ->join('users', 'users.id', '=', 'borrows.user_id')
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'users.name', 'users.surname', 'borrows.borrows_date', 'borrows.returns_date',
                'borrows.id', 'borrows.when_returned', 'borrows.delay', 'borrows.penalty'
            )
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * Display a listing of borrowings which are delayed.
     *
     * @return Response
     */
    public function getDelayedBorrows()
    {
        $this->checkDelay();
        $data = DB::table('borrows')
            ->where('borrows.delay', '!=', 'null')
            ->join('users', 'users.id', '=', 'borrows.user_id')
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'users.name', 'users.surname', 'borrows.delay', 'borrows.penalty'  
            )
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * Check is there is any delayed book.
     *
     * @return Response
     */
    public function checkDelay()
    {
        $returnDates = Borrow::pluck('returns_date');
        $todayDate = Carbon::now();

        for($index = 0; $index < sizeof($returnDates); $index++) {
            $date = $returnDates[$index];
            $dateOfReturn = Carbon::createFromFormat('Y-m-d', $date)->toFormattedDateString();
            $differenceInDays[$index] = $todayDate->diffInDays($dateOfReturn, false);
            if($differenceInDays[$index] < 0) {
                $delay = $differenceInDays[$index] * -1;
                $penalty = $differenceInDays[$index] * -0.5;
                $borrow = Borrow::where('returns_date', '=', $returnDates[$index])->first();
                $borrow->delay = $delay;
                $borrow->penalty = $penalty;
                $borrow->save();
            }
        }
        if ($borrow->save()) {
            return response()->json(
                [
                'success' => true,
                'book' => $borrow
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing could not be added.',
                ], 500
            );
        }
    }

    /**
     * Return specific book to the library.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnBook(Request $request, $id)
    {
        $borrow = Borrow::find($id);
        $todayDate = Carbon::now();

        $borrow->is_returned = $request->is_returned;
        $borrow->when_returned = $todayDate;

        $book = Book::find($request->bookID);
        $book->amount = $book->amount+1;

        $book->save();
        $borrow->save();

        if ($borrow->save()) {
            return response()->json(
                [
                'success' => true,
                'book' => $borrow
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing could not be added.',
                ], 500
            );
        }
    }

    /**
     * Edit specific borrowing.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editBorrow(Request $request, $id)
    {
        $borrow = Borrow::find($id);

        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $borrow->borrows_date = $request->borrows_date;
        $date = Carbon::createFromFormat('Y-m-d', $request->borrows_date);
        $daysToAdd = 30;
        $date = $date->addDays($daysToAdd);
        $borrow->returns_date = $date->toDateString();
        $borrow->save();

        if ($borrow->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Borrowing has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Show borrowings for a specific user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showBorrow($id)
    {
        $borrow = DB::table('borrows')
            ->where('borrows.user_id', '=', $id)
            ->where('borrows.when_returned', '=', null)
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->select(
                'books.id', 'books.title', 'authors.name', 'authors.surname', 'borrows.borrows_date', 
                'borrows.returns_date'
            )
            ->get()
            ->toArray();

        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing with id ' . $id . ' cannot be found.'
                ], 400
            );
        } else {
            return $borrow;
        } 
    }

    /**
     * Show delays for a specific user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showDelay($id)
    {
        $borrow = DB::table('borrows')
            ->where('borrows.user_id', '=', $id)
            ->where('borrows.delay', '!=', null)
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->select(
                'books.title', 'authors.name', 'authors.surname', 'borrows.delay', 'borrows.penalty'
            )
            ->get()
            ->toArray();

        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing with id ' . $id . ' cannot be found.'
                ], 400
            );
        } else {
            return $borrow;
        } 
    }

    /**
     * Extend returns book deadline.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function extendDate(Request $request, $id)
    {
        $borrow = Borrow::find($id);

        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $date = Carbon::createFromFormat('Y-m-d', $borrow->returns_date);
        $daysToAdd = 7;
        $date = $date->addDays($daysToAdd);
        $borrow->returns_date = $date->toDateString();
        $borrow->save();

        if ($borrow->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Borrowing has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Get amount of borrowings for all months.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAmount($id)
    {
        $borrow = Borrow::where('user_id', '=', $id)
            ->select('borrows_date')
            ->get()
            ->groupBy(
                function ($date) {
                    return Carbon::parse($date->borrows_date)->format('m'); // grouping by months
                }
            );
            
        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, user has no borrowings.'
                ], 400
            );
        }

        $borrowsCount = [];
        $amount = [];
        
        foreach ($borrow as $key => $value) {
            $borrowsCount[(int)$key] = count($value);
        }
        
        for($i = 1; $i <= 12; $i++){
            if(!empty($borrowsCount[$i])) {
                $amount[$i] = $borrowsCount[$i];    
            }else{
                $amount[$i] = 0;    
            }
        }

        return $amount;
    }

    /**
     * Get favorite authors.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAuthors($id)
    {
        $borrow = Borrow::where('user_id', '=', $id)
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->select('authors.name', 'authors.surname', DB::raw('COUNT(authors.name + authors.surname) as count'))
            ->groupBy('authors.surname')
            ->orderBy('count')
            ->take(5)
            ->get();

        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, user has no borrowings.'
                ], 400
            );
        }

        return $borrow;
    }

    /**
     * Get preferable category.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getCategory($id)
    {
        $borrow = Borrow::where('user_id', '=', $id)
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->select('categories.name', DB::raw('COUNT(categories.name) as count'))
            ->groupBy('categories.name')
            ->orderBy('count')
            ->take(5)
            ->get();

        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, user has no borrowings.'
                ], 400
            );
        }

        return $borrow;
    }
}
