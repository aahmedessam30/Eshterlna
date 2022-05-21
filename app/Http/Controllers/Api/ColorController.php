<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except('index', 'show');
    }

    public function index()
    {
        return ColorResource::collection(Color::whereOnline()->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(ColorRequest $request)
    {
        $color = Color::create($request->safe()->merge(['user_id' => auth('api')->id()])->all());

        // Send Notification For All Customers
        sendFireBaseNotification(User::customer()->get(), __('notification.new_color', ['name' => $color->name]));

        return (new ColorResource($color))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Color $color)
    {
        return (new ColorResource($color))
            ->additional(['status' => true]);
    }

    public function update(ColorRequest $request, Color $color)
    {
        $response = Gate::inspect('update', $color);

        if ($response->allowed()) {

            $color->update($request->safe()->merge(['user_id' => auth('api')->id()])->all());

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.update_color', ['name' => $color->name]));

            return (new ColorResource($color))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Color $color)
    {
        $response = Gate::inspect('delete', $color);

        if ($response->allowed()) {

            $color->delete();

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.delete_color', ['name' => $color->name]));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
