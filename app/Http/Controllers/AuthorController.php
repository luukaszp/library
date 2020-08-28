<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;

class AuthorController extends Controller
{
    //pokazac info autora + ksiazki
    /**
     * Create a new author.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addAuthor(Request $request)
    {
        $this->validate(
            $request, [
            'name' => 'required',
            'surname' => 'required',
            ]
        );

        $author = new Author();
        $author->name = $request->name;
        $author->surname = $request->surname;

        $author->save();

        return response()->json(
            [
            'success' => true,
            'author' => $author
            ], 201
        );
    }

    /**
     * Edit specific author.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editAuthor(Request $request, $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, author with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $author->name = $request->name;
        $author->surname = $request->surname;
        $author->save();

        if ($author->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Author has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, author could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified author.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAuthor($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, author with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($author->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Author could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Display a listing of authors.
     *
     * @return Response
     */
    public function getAuthors()
    {
        $author = Author::get(['id', 'name', 'surname'])->toArray();
        return $author;
    }
}
