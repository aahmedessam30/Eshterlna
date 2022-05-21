<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except('index', 'show');
    }

    public function index()
    {
        $stores = Auth::check() && Auth::user()->type == 'merchant'
            ? Store::whereOnline()->auth()
            : Store::whereOnline();

        return StoreResource::collection($stores->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(StoreRequest $request)
    {
        $store = Store::create($request->safe()->merge(['user_id' => Auth::id()])->all());

        // Send Notification For Authorized Merchant
        sendFireBaseNotification(Auth::user(), __('notification.store_added' ,['name' => $store->name]));

        return (new StoreResource($store))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Store $store)
    {
        return (new StoreResource($store))->additional(['status' => true]);
    }

    public function update(StoreRequest $request, Store $store)
    {
        $response = Gate::inspect('update', $store);

        if ($response->allowed()) {

            $store->update($request->safe()->merge(['user_id' => Auth::id()])->all());

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.store_updated' ,['name' => $store->name]));

            return (new StoreResource($store))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Store $store)
    {
        $response = Gate::inspect('delete', $store);

        if ($response->allowed()) {

            $store->delete();

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.store_deleted' ,['name' => $store->name]));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}
