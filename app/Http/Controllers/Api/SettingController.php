<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant']);
    }

    public function index()
    {
        return SettingResource::collection(Setting::where('user_id', Auth::id())->first())
            ->additional(['status' => true]);
    }

    public function store(SettingRequest $request)
    {
        $setting = Setting::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        // Send Notification For Authorized Merchant
        sendFireBaseNotification(Auth::user(), __('notification.setting_added'));

        return (new SettingResource($setting))
            ->additional(['status' => true, 'message' => __('messages.store_success.')]);
    }

    public function show(Setting $setting)
    {
        return (new SettingResource($setting))->additional(['status' => true]);
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        $response = Gate::inspect('update', $setting);

        if ($response->allowed()) {
            $setting->update($request->safe()->merge(['user_id' => Auth::id()])->all());

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.setting_updated'));

            return (new SettingResource($setting))
                ->additional(['status' => true, 'message' => __('messages.update_success.')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }

    }

    public function destroy(Setting $setting)
    {
        $response = Gate::inspect('delete', $setting);

        if ($response->allowed()) {
            $setting->delete();

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.setting_deleted'));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
