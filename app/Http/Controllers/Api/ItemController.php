<?php

namespace App\Http\Controllers\Api;

use App\Models\{User, Item, Store};
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ItemResource;
use App\Http\Resources\BasicResource;


class ItemController extends Controller
{
    protected $relationships = ['category', 'brand', 'merchant', 'vat'];

    public function __construct()
    {
        $this->middleware(['auth:api', 'merchant'])->except(['index', 'show']);

        if (getSettings()->size == 1) {
            array_push($this->relationships, 'sizes');
        }

        if (getSettings()->color == 1) {
            array_push($this->relationships, 'colors');
        }

        if (getSettings()->store == 1) {
            array_push($this->relationships, 'stores');
        }
    }

    public function index()
    {
        $items = Auth::check() && Auth::user()->type == 'merchant'
            ? Item::whereOnline()->auth()
            : Item::whereOnline();

        return ItemResource::collection($items->with($this->relationships)
            ->latest('id')->paginate(config('global.pagination')))
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

        return (new ItemResource($item->with($this->relationships)))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Item $item)
    {
        return (new ItemResource($item->load($this->relationships)))
            ->additional(['status' => true]);
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

            return (new ItemResource($item->with($this->relationships)))
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
