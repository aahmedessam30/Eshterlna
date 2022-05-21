<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SizeRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\SizeResource;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SizeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except('index', 'show');
    }

    public function index()
    {
        $sizes = Auth::check() && Auth::user()->type == 'merchant'
            ? Size::whereOnline()->auth()
            : Size::whereOnline();

        return SizeResource::collection($sizes->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => 'success']);
    }

    public function store(SizeRequest $request)
    {
        $size = Size::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        // Send Notification For Authorized Merchant
        sendFireBaseNotification(Auth::user(), __('notification.size_added', ['name' => $size->name]));

        return (new SizeResource($size))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Size $size)
    {
        return (new SizeResource($size))
            ->additional(['status' => true]);
    }

    public function update(SizeRequest $request, Size $size)
    {
        $response = Gate::inspect('update', $size);

        if ($response->allowed()) {

            $size->update($request->safe()->merge(['user_id' => Auth::id()])->all());

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.size_updated', ['name' => $size->name]));

            return (new SizeResource($size))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Size $size)
    {
        $response = Gate::inspect('delete', $size);

        if ($response->allowed()) {

            $size->delete();

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.size_deleted', ['name' => $size->name]));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
