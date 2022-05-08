<?php

use App\Models\Setting;

function responseMsg($status, $message)
{
    return response()->json(['status' => $status, 'message' => $message]);
}

function getSettings()
{
    return Setting::first();
}
