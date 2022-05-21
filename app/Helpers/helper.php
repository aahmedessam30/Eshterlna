<?php

use App\Models\Setting;
use App\Notifications\FirebaseNotificaion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

function getSettings()
{
    return Setting::first();
}

 function sendNotification($tokens , $content , $data = [])
{
    //FCM api URL
    $url = 'https://fcm.googleapis.com/fcm/send';
    $server_key = config('global.server_key');

    //header
    $headers =[
        'Content-Type'  => 'application/json',
        'Authorization' => 'key='.$server_key,
    ];

    //Notification payload
    $notification = [
        'title'        => env('APP_NAME'),
        'body'         => $content,
        'sound'        => true,
        'click_action' => 'MAIN_ACTIVITY',
    ];

    //Body
    $fields = [
        'registration_ids' => $tokens,
        'notification'     => $notification,
    ];

    if(!empty($data)){
        $fields['data'] = $data;
    }

    //Http request to connect with FCM
    $response = Http::withHeaders($headers)->post($url, $fields);

    return $response;
}

 function sendFireBaseNotification($users, $message, $data = [])
{
    Notification::send($users, new FirebaseNotificaion($message));

    $tokens = $users->pluck('device_key')->toArray();

    $tokens = array_filter($tokens);
    $tokens = array_unique($tokens);
    $tokens = array_values($tokens);

    sendNotification($tokens, $message, $data);
}
