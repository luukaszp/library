<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use Intervention\Image\Facades\Image;

class AuthorController extends Controller
{
    /**
     * Create a new author.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addAuthor(Request $request)
    {
        $author = new Author();
        $author->name = $request->get('name');
        $author->surname = $request->get('surname');
        $author->description = $request->get('description');

        if ($file = $request->hasFile('photo')) {
            $author->photo = $imagePath = $request->file('photo')->store('authors', 'public');

            $photo = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $photo->save();

            $imageArray = ['photo' => $imagePath];
        }

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
        $author->description = $request->description;
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
        $author->books()->attach($author);

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
        $author = Author::get(['id', 'name', 'surname', 'description', 'photo'])->toArray();
        return $author;
    }

    /**
     * Display book by id
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAuthor($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, author with id ' . $id . ' cannot be found.',
                ], 400
            );
        }

        return $author;
    }

    /**
     * Edit current author photo
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePhoto(Request $request, $id) //create StoreBook
    {
        $author = Author::find($id);

        if ($file = $request->hasFile('photo')) {
            $author->photo = $imagePath = $request->file('photo')->store('authors', 'public');

            $photo = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $photo->save();

            $imageArray = ['photo' => $imagePath];
        }

        if ($author->save()) {
            return response()->json(
                [
                'success' => true,
                'author' => $author
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, author could not be added.',
                ], 500
            );
        }
    }
}
