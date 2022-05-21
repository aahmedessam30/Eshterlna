<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $notifications = auth()->user()->notifications;
        return NotificationResource::collection($notifications)
            ->additional(['status' => true]);
    }

    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->read_at != null ?? $notification->markAsRead();

        return (new NotificationResource($notification))->additional(['status' => true]);
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return (new NotificationResource($notification))->additional(['status' => true]);
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return NotificationResource::collection(auth()->user()->notifications)->additional(['status' => true]);
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return new BasicResource(true, __('messages.delete_success') , 'message');
    }
}
