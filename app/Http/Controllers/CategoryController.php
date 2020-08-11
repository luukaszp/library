<?php

namespace App\Http\Controllers;

use App\Category;
use App\Book;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Create a new category.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->name;

        $category->save();

        return response()->json([
            'success' => true,
            'category' => $category
        ], 201);
    }

    /**
     * Edit specific category.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editCategory(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category with id ' . $id . ' cannot be found.'
            ], 400);
        }

        $updated = $category->name = $request->name;
        $category->save();

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Category has been updated',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category could not be updated.',
            ], 500);
        }
    }

    /**
     * Remove the specified category.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category with id ' . $id . ' cannot be found.'
            ], 400);
        }

        if ($category->destroy($id)) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Category could not be deleted.'
            ], 500);
        }
    }

    /**
     * Remove the specified book from specified category.
     *
     * @param Category $category
     * @param Book $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function remove(Category $category, $id)
    {
        $book = Book::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category cannot be found.'
            ], 400);
        }

        if ($category->books()->detach($book)) {
            return response()->json([
                'success' => true,
                'message' => 'Book from given category was successfully removed.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Book from given category could not be deleted.'
            ], 500);
        }
    }

    /**
     * Display a listing of categories.
     *
     * @return Response
     */
    public function getCategories()
    {
        $category = Category::get(['id', 'name'])->toArray();
        return $category;
    }

    /**
     * Display books from specified category
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category with id ' . $id . ' cannot be found.'
            ], 400);
        }

        $currentCategory = $category->books()->get(['title', 'description', 'publish_year'])->toArray();

        return response()->json([
            'success' => true,
            'category' => $currentCategory,
        ]);
    }
}
