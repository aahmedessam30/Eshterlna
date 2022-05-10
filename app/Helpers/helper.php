<?php

use App\Models\Setting;

function getSettings()
{
    return Setting::first();
}
