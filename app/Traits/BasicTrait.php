<?php

namespace App\Traits;

use App\Notifications\FirebaseNotificaion;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

trait BasicTrait
{
    public function setImage($image, $path)
    {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path($path), $imageName);
        return $imageName;
    }

    public function getImage($image, $path)
    {
        return !is_null($image) ? asset($path . '/' . $image) : asset('images/NoImageFound.jpg');
    }
}
