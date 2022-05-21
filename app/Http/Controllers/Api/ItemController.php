<?php

namespace App\Http\Controllers\Api;

use App\Helpers;
use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\App;
use App\Http\Resources\ItemResource;
use App\Http\Resources\BasicResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except(['index', 'show']);
    }

    public function index()
    {
        return ItemResource::collection(Item::whereOnline()->latest('id')->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(ItemRequest $request)
    {
        $validated = $request->safe();

        $item = Item::create($validated->merge(['user_id' => Auth::id()])->all());

        if ($request->has('sizes') && getSettings()->size == 1) {
            foreach ($validated['sizes'] as $size) {
                $item->sizes()->attach(['size_id' => $size]);
            }
        }

        if ($request->has('colors') && getSettings()->color == 1) {
            foreach ($validated['colors'] as $color) {
                $item->colors()->attach(['color_id' => $color]);
            }
        }

        if ($request->has('stores') && getSettings()->store == 1) {
            foreach ($validated['stores'] as $index => $store) {
                $item->stores()->attach(['store_id' => $store], ['quantity' => $validated['quantity'][$index]]);
            }
        }

        // Send Notification For All Customers
        sendFireBaseNotification(User::customer()->get(), __('notification.new_item', ['name' => $item->name]));

        return (new ItemResource($item))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Item $item)
    {
        return (new ItemResource($item))->additional(['status' => true]);
    }

    public function update(ItemRequest $request, Item $item)
    {
        $response = Gate::inspect('update', $item);

        if ($response->allowed()) {

            $validated = $request->safe();

            $item->update($validated->merge(['user_id' => Auth::id()])->all());

            if ($request->has('sizes') && getSettings()->size == 1) {
                foreach ($validated['sizes'] as $size) {
                    $item->sizes()->sync(['size_id' => $size]);
                }
            }

            if ($request->has('colors') && getSettings()->color == 1) {
                foreach ($validated['colors'] as $color) {
                    $item->colors()->sync(['color_id' => $color]);
                }
            }

            if ($request->has('store_id') && getSettings()->store == 1) {
                $item->stores()->updateExistingPivot($validated['store_id'], ['quantity' => $validated['quantity']]);
            }

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.update_item', ['name' => $item->name, 'store' => Store::find($validated['store_id'])->name]));

            return (new ItemResource($item))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function destroy(Item $item)
    {
        $response = Gate::inspect('delete', $item);

        if ($response->allowed()) {

            $item->delete();

            // Send Notification For Authorized Merchant
            sendFireBaseNotification(Auth::user(), __('notification.delete_item', ['name' => $item->name]));

            return new BasicResource(true, __('messages.delete_success'), 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function deleteItemStore(Item $item, Store $store)
    {
        $response = Gate::inspect('delete', $item);

        if ($response->allowed()) {

            if ($item->stores->contains($store->id)) {

                // Delete item from store
                $item->stores()->detach($store->id);

                // Send Notification For Authorized Merchant
                sendFireBaseNotification(Auth::user(), __('notification.delete_item_store', ['name' => $item->name, 'store' => $store->name]));

                return new BasicResource(true, __('messages.delete_success'), 'message');
            } else {
                return new BasicResource(false, __('messages.not_found'), 'message');
            }
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }
}

