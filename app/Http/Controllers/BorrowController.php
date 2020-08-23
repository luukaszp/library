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
}
