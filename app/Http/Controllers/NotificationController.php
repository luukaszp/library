<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for a specific user.
     *
     * @return Response
     */
    public function getNotifications($id)
    {
        $user = User::find($id);

        $notifications = $user->unreadNotifications;
        return $notifications;
    }

    /**
     * Mark notifications as read for a specific user.
     *
     * @return Response
     */
    public function markAsRead($id)
    {
        $user = User::find($id);

        $user->unreadNotifications->markAsRead();
    }
}
