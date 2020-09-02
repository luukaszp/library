<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{
    /**
     * Create a new event.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addEvent(Request $request)
    {
        $this->validate(
            $request, [
            'name' => 'required',
            'date' => 'required',
            'time' => 'required'
            ]
        );

        $event = new Event();
        $event->name = $request->name;
        $event->date = $request->date;
        $event->time = $request->time;

        $event->save();

        return response()->json(
            [
            'success' => true,
            'event' => $event
            ], 201
        );
    }

    /**
     * Edit specific event.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editEvent(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, event with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $event->name = $request->name;
        $event->date = $request->date;
        $event->time = $request->time;

        $event->save();

        if ($event->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Event has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, event could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified event.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEvent($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, event with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($event->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Event could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Display a listing of events.
     *
     * @return Response
     */
    public function getEvents()
    {
        $event = Event::get(['id', 'name', 'date', 'time'])->toArray();
        return $event;
    }
}
