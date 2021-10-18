<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Borrow;
use App\Book;
use App\User;
use App\Reader;
use App\Worker;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
            $readerID = Reader::where('user_id', '=', $request->reader_id)->pluck('id');
            $borrow->reader_id = $readerID[0];
            $workerID = Worker::where('user_id', '=', Auth::user()->id)->pluck('id');
            $borrow->worker_id = $workerID[0];
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
            $book->is_available = 0;
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
            ->where('borrows.when_returned', '=', null)
            ->join('readers', 'readers.id', '=', 'borrows.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'users.name', 'users.surname', 'borrows.borrows_date', 'borrows.returns_date', 'borrows.id', 'books.id as bookID'
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
            ->where('borrows.when_returned', '!=', null)
            ->join('readers', 'readers.id', '=', 'borrows.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'users.name', 'users.surname', 'borrows.borrows_date', 'borrows.returns_date',
                'borrows.id', 'borrows.when_returned', 'borrows.delay', 'borrows.penalty', 'borrows.worker_id'
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
            ->join('readers', 'readers.id', '=', 'borrows.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'users.name', 'users.surname', 'borrows.delay', 'borrows.penalty'
            )
            ->groupBy('books.id', 'books.title', 'users.name', 'users.surname', 'borrows.delay', 'borrows.penalty')
            ->get()
            ->toArray();

        return $data;
    }

    /**
     * Check if there is any delayed book.
     *
     * @return Response
     */
    public function checkDelay()
    {
        $returnDates = Borrow::pluck('returns_date');
        $todayDate = Carbon::now();
        $howMany = 0;

        for($index = 0; $index < sizeof($returnDates); $index++) {
            $date = $returnDates[$index];
            $dateOfReturn = Carbon::createFromFormat('Y-m-d', $date)->toFormattedDateString();
            $differenceInDays[$index] = $todayDate->diffInDays($dateOfReturn, false);

            if($index >= 1) {
                if($returnDates[$index] !== $returnDates[$index - 1]) {
                    $howMany = 0;
                }
            }

            if($differenceInDays[$index] < 0) {
                $borrow = Borrow::where('returns_date', '=', $returnDates[$index])->skip($howMany)->first();

                if($borrow->when_returned === null) {
                    $delay = $differenceInDays[$index] * -1;
                    $penalty = $differenceInDays[$index] * -0.5;
                    $borrow->delay = $delay;
                    $borrow->penalty = $penalty;
                    $borrow->save();
                }

                $howMany++;
            }
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

        $borrow->when_returned = $todayDate;

        $book = Book::find($request->bookID);
        $book->is_available = $request->is_available;

        $reader = Reader::find($borrow->reader_id);
        $reader->can_extend = 1;

        $reader->save();
        $book->save();
        $borrow->save();

        $this->checkDelay();

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
     * Show borrowings for a specific reader.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showBorrow($id)
    {
        $reader = Reader::where('user_id', '=', $id)->pluck('id');
        $borrow = DB::table('borrows')
            ->where('borrows.reader_id', '=', $reader[0])
            ->where('borrows.when_returned', '=', null)
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'borrows.id', 'books.title', 'borrows.borrows_date',
                'borrows.returns_date'
            )
            ->get()
            ->toArray();

        return $borrow;
    }

    /**
     * Show delays for a specific reader.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showDelay($id)
    {
        $reader = Reader::where('user_id', '=', $id)->pluck('id');
        $delay = DB::table('borrows')
            ->where('borrows.reader_id', '=', $reader[0])
            ->where('borrows.delay', '!=', null)
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'borrows.delay', 'borrows.penalty'
            )
            ->get()
            ->toArray();

        return $delay;
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

        $reader = Reader::find($borrow->reader_id);

        if ($reader->can_extend === '0') {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, reader cannot extend the date any further.'
                ], 400
            );
        }

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

        $reader->can_extend = 0;
        $reader->save();

        if ($borrow->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Borrowing has been extended by 7 days',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, borrowing could not be expended any further.',
                ], 500
            );
        }
    }

    /**
     * Get amount of borrowings for all months (every month for one reader)
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAmount($id)
    {
        $reader = Reader::where('user_id', '=', $id)->pluck('id');
        $borrow = Borrow::where('reader_id', '=', $reader[0])
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
                'message' => 'Sorry, reader has no borrowings.'
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
     * Get amount of borrowings for specific month
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getMonthlyAmount()
    {
        $position = [];
        $status = [];

        $firstDay = Carbon::now()->startOfMonth()->toDateString(); //current month
        $lastDay = Carbon::now()->endOfMonth()->toDateString();

        $lastMonthFDay = Carbon::now()->subMonth()->startOfMonth()->toDateString(); //previous month
        $lastMonthLDay = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $previousMonth = DB::table('borrows')
            ->whereBetween('borrows.borrows_date', [$lastMonthFDay, $lastMonthLDay])
            ->join('readers', 'readers.id', '=', 'borrows.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->select(
                'users.name', 'users.surname', 'users.avatar', DB::raw('COUNT(borrows.borrows_date) as count')
            )
            ->groupBy('users.id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        $currentMonth = DB::table('borrows')
            ->whereBetween('borrows.borrows_date', [$firstDay, $lastDay])
            ->join('readers', 'readers.id', '=', 'borrows.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->select(
                'users.name', 'users.surname', 'users.avatar', DB::raw('COUNT(borrows.borrows_date) as count')
            )
            ->groupBy('users.id')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();


        if($previousMonth->first() === null) {
            return $currentMonth;
        }

        for ($i = 0; $i < count($previousMonth); $i++) {
            if ($previousMonth[$i]->surname !== $currentMonth[$i]->surname) {
                for ($j = 0; $j < count($currentMonth); $j++) {
                    if ($previousMonth[$i]->surname === $currentMonth[$j]->surname) {
                        $position[$j] = $i - $j;
                    }
                }
            } else {
                $position[$i] = 0;
            }
        }

        for ($i = 0; $i <= key($position); $i++) {
            if (!array_key_exists($i, $position)) {
                $status[$i] = 'new';
            } else if ($position[$i] > 0) {
                $status[$i] = 'up';
            } else if ($position[$i] < 0) {
                $status[$i] = 'down';
            } else {
                $status[$i] = 'same';
            }
        }

        for ($i = 0; $i < count($status); $i++) {
            $currentMonth[$i]->status = $status[$i];
            if ($status[$i] === 'down') {
                $position[$i] *= -1;
            }
            if (!array_key_exists($i, $position)) {
                $currentMonth[$i]->position = 0;
            } else {
                $currentMonth[$i]->position = $position[$i];
            }
        }

        return $currentMonth;
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
        $reader = Reader::where('user_id', '=', $id)->pluck('id');
        $borrow = Borrow::where('reader_id', '=', $reader[0])
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->join('author_book', 'author_book.book_id', '=', 'books.id')
            ->join('authors', 'authors.id', '=', 'author_book.author_id')
            ->select('authors.name', 'authors.surname', DB::raw('COUNT(authors.name + authors.surname) as count'))
            ->groupBy('authors.surname')
            ->orderBy('count')
            ->take(5)
            ->get();

        if (!$borrow) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, reader has no borrowings.'
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
        $reader = Reader::where('user_id', '=', $id)->pluck('id');
        $borrow = Borrow::where('reader_id', '=', $reader[0])
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
                'message' => 'Sorry, reader has no borrowings.'
                ], 400
            );
        }

        return $borrow;
    }
}
