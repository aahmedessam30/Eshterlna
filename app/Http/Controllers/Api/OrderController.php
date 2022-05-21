<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\OrderResource;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return OrderResource::collection(Order::auth()->latest()->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(OrderRequest $request)
    {
        $merchants = collect();
        $validated = $request->safe();
        $order_details = $this->calculateOrder($validated['items_id'], $validated['quantity']);

        $order = Order::create($validated->merge([
            'user_id'        => Auth::id(),
            'total_price'    => $order_details['total_price'],
            'total_discount' => $order_details['total_discount'],
            'total_tax'      => $order_details['total_tax'],
            'total_shipping' => $order_details['total_shipping'],
            'total_weight'   => $order_details['total_weight'],
            'total_items'    => $order_details['total_items'],
        ])->all());

        foreach ($order_details['item_detail'] as $item) {
            $order->items()->attach($item['item_id'] , $item);

            $merchant = Item::find($item['item_id'])->merchant;
            $merchants->push($merchant);
        }

        // Send Notification For Merchant
        SendFireBaseNotification($merchants, __('notification.new_order_merchant' , ['order_id' => $order->id , 'customer' => $order->user->name]));

        // Send Notification For Customer
        sendFireBaseNotification(Auth::user(), __('notification.new_order_customer' , ['order_id' => $order->id]));

        return (new OrderResource($order))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Order $order)
    {
        return (new OrderResource($order->load('items')))->additional(['status' => true]);
    }

    public function update(OrderRequest $request, Order $order)
    {
        $response = Gate::inspect('update', $order);

        if ($response->allowed()) {
            $merchants = collect();
            $validated = $request->safe();
            $order_details = $this->calculateOrder($validated['items_id'], $validated['quantity']);

            $order->update($validated->merge([
                'user_id'        => Auth::id(),
                'total_price'    => $order_details['total_price'],
                'total_discount' => $order_details['total_discount'],
                'total_tax'      => $order_details['total_tax'],
                'total_shipping' => $order_details['total_shipping'],
                'total_weight'   => $order_details['total_weight'],
                'total_items'    => $order_details['total_items'],
                'ordered_at'     => now(),
            ])->all());

            // Detach all items
            $order->items()->detach();

            // Attach new items
            foreach ($order_details['item_detail'] as $item) {
                $order->items()->attach($item['item_id'] , $item);

                $merchant = Item::find($item['item_id'])->merchant;
                $merchants->push($merchant);
            }

            // Send Notification For Merchant
            SendFireBaseNotification($merchants, __('notification.update_order_merchant' , ['order_id' => $order->id , 'customer' => $order->user->name]));

            // Send Notification For Customer
            sendFireBaseNotification(Auth::user(), __('notification.update_order_customer' , ['order_id' => $order->id]));

            return (new OrderResource($order))
                ->additional(['status' => true, 'message' => __('messages.update_success')]);
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }

    }

    public function destroy(Order $order)
    {
        $response = Gate::inspect('delete', $order);

        if ($response->allowed()) {

            $merchants = collect();

            // Add Merchant to Collection
            $order->items()->each(fn($item)=> $merchants->push($item->merchant));

            // Remove Order
            $order->delete();

            // Send Notification For Merchant
            SendFireBaseNotification($merchants, __('notification.delete_order_merchant' , ['order_id' => $order->id , 'customer' => $order->user->name]));

            // Send Notification For Customer
            sendFireBaseNotification(Auth::user(), __('notification.delete_order_customer' , ['order_id' => $order->id]));

            return new BasicResource(true, __('messages.delete_success') , 'message');
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function calculateOrder($items, $quantity)
    {
        $order = collect();
        $item_detail = collect();
        $sale_tax = 0;
        $pay_tax = 0;
        $total_basic_price = 0;
        $total_price = 0;
        $total_discount = 0;
        $total_tax = 0;
        $total_shipping = 0;
        $total_weight = 0;

        foreach ($items as $index => $item) {
            $item = Item::find($item);
            $sale_price = $item->sale_price;
            $pay_price = $item->pay_price;

            // If item is not included tax
            if ($item->vat_state == 1) {
                $sale_tax = $item->sale_price * ($item->vat->value / 100);
                $pay_tax = $item->pay_price * ($item->vat->value / 100);
            }

            // If item is included tax
            if ($item->vat_state == 2) {
                $sale_price = $item->sale_price / (1 + $item->vat->value / 100);
                $pay_price = $item->pay_price / (1 + $item->vat->value / 100);

                $sale_price = round($sale_price, 2);
                $sale_tax = $item->sale_price - $sale_price;

                $pay_price = round($pay_price, 2);
                $pay_tax = $item->pay_price - $pay_price;
            }

            $item_detail->put($index, [
                'item_id'  => $item->id,
                'quantity' => $quantity[$index],
                'price'    => round($sale_price * $quantity[$index], 2),
                'tax'      => round($sale_tax * $quantity[$index], 2),
                'shipping' => round($item->shipping * $quantity[$index], 2),
                'weight'   => round($item->weight * $quantity[$index], 2),
                'discount' => round($item->discount * $quantity[$index], 2),
            ]);

            $total_basic_price += $sale_price * $quantity[$index];
            $total_discount += $item->discount * $quantity[$index];
            $total_tax += $sale_tax * $quantity[$index];
            $total_shipping += $item->shipping * $quantity[$index];
            $total_weight += $item->weight * $quantity[$index];
            $total_price = $total_basic_price + $total_tax + $total_shipping + $total_weight - $total_discount;

            $order['item_detail'] = $item_detail;
            $order['total_basic_price'] = round($total_basic_price, 2);
            $order['total_discount'] = round($total_discount, 2);
            $order['total_tax'] = round($total_tax, 2);
            $order['total_shipping'] = round($total_shipping, 2);
            $order['total_weight'] = round($total_weight, 2);
            $order['total_price'] = round($total_price, 2);
            $order['total_items'] = count($items);
        }

        return $order;
    }
}
