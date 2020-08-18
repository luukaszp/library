<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Borrow;
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

        for($index = 0; $index < count($book); $index++) {
            $borrow = new Borrow();
            $borrow->user_id = $request->user_id;
            $borrow->borrows_date = $request->borrows_date;

            $idOfBook = $request->book_id[$index];
            $stringToInt = (int)$idOfBook;
            $borrow->book_id = $stringToInt;

            $date = Carbon::createFromFormat('d.m.Y', $request->borrows_date);
            $daysToAdd = 30;
            $date = $date->addDays($daysToAdd);
            $borrow->returns_date = $date->format('d.m.Y');
            $borrow->save();
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
     * Display a listing of borrowings.
     *
     * @param  Request $request
     * @return 
     */
    public function getBorrows(Request $request)
    {
        $data = DB::table('borrows')
            ->join('users', 'users.id', '=', 'borrows.user_id')
            ->join('books', 'books.id', '=', 'borrows.book_id')
            ->select(
                'books.title', 'users.name', 'users.surname', 'borrows.borrows_date', 'borrows.returns_date' 
            )
            ->get()
            ->toArray();
        return $data;
    }
}
