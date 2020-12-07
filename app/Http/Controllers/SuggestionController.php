<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suggestion;
use DB;

class SuggestionController extends Controller
{
    /**
     * Create a new suggestion.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addSuggestion(Request $request)
    {
        $suggestion = new Suggestion();
        $suggestion->type = $request->type;
        $suggestion->description = $request->description;
        $suggestion->reader_id = auth()->user()->id;

        $suggestion->save();

        return response()->json(
            [
            'success' => true,
            'suggestion' => $suggestion
            ], 201
        );
    }

    /**
     * Remove the specified suggestion.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSuggestion($id)
    {
        $suggestion = Suggestion::find($id);

        if (!$suggestion) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, suggestion with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($suggestion->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Suggestion could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Display a listing of suggestions.
     *
     * @return Response
     */
    public function getSuggestions()
    {
        $suggestion = DB::table('suggestions')
            ->join('readers', 'readers.id', '=', 'suggestions.reader_id')
            ->join('users', 'users.id', '=', 'readers.user_id')
            ->select(
                'suggestions.id', 'suggestions.type', 'suggestions.description', 'users.name', 'users.surname'
            )
            ->get()
            ->toArray();

        return $suggestion;
    }
}
