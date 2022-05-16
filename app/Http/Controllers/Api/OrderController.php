<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\BasicResource;
use App\Http\Resources\OrderResource;
use App\Models\Item;
use App\Models\Order;
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
        return OrderResource::collection(Order::latest()->paginate(config('global.pagination')))
            ->additional(['status' => true]);
    }

    public function store(OrderRequest $request)
    {
        $validated = $request->safe();
        return $this->calculateOrder($validated['items_id'] , $validated['quantity']);
        $order = Order::create($validated->merge(['user_id' => Auth::id()])->all());

        $order->items()->attach(
            ['item_id' => $validated['item_id']],
            ['quantity' => $validated['quantity']],
            ['price' => $validated['price']]
        );

        return (new OrderResource($order))
            ->additional(['status' => true, 'message' => __('messages.store_success')]);
    }

    public function show(Order $order)
    {
        return (new OrderResource($order))->additional(['status' => true]);
    }

    public function update(OrderRequest $request, Order $order)
    {
        $response = Gate::inspect('update', $order);

        if ($response->allowed()) {
            $validated = $request->safe();
            $order->update($validated->merge(['user_id' => Auth::id()])->all());

//            $order->items()->updateExistingPivot(
//                ['item_id'  => $validated['item_id']],
//                ['quantity' => $validated['quantity']],
//                ['price'    => $validated['price']]
//            );

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
            $order->delete();
            return new BasicResource(true, __('messages.delete_success'));
        } else {
            return new BasicResource(false, $response->message(), 'message');
        }
    }

    public function calculateOrder($items , $quantity)
    {
        $order = [];
        $sale_tax = 0;
        $pay_tax = 0;
        $total_price = 0;
        $total_discount = 0;
        $total_tax = 0;
        $total_shipping = 0;
        $total_weight = 0;

        foreach ($items as $index => $item) {
            $item = Item::find($item);
            $sale_price = $item->sale_price;
            $pay_price = $item->pay_price;

            if ($item->vat_state == 1) {
                $sale_tax = $item->sale_price * ($item->vat->value / 100);
                $pay_tax = $item->pay_price * ($item->vat->value / 100);
            }

            if ($item->vat_state == 2) {
                $sale_price = $item->sale_price / (1 + $item->vat->value / 100);
                $pay_price = $item->pay_price / (1 + $item->vat->value / 100);

                $sale_price = round($sale_price, 2);
                $sale_tax = $item->sale_price - $sale_price;

                $pay_price = round($pay_price, 2);
                $pay_tax = $item->pay_price - $pay_price;
            }

            $total_price += $sale_price * $quantity[$index];
            $total_discount += $item->discount * $quantity[$index];
            $total_tax += $sale_tax * $quantity[$index];
            $total_shipping += $item->shipping * $quantity[$index];
            $total_weight += $item->weight * $quantity[$index];

            $order['total_price'] = $total_price;
            $order['total_discount'] = $total_discount;
            $order['total_tax'] = $total_tax;
            $order['total_shipping'] = $total_shipping;
            $order['total_weight'] = $total_weight;
        }

        return $order;
    }
}
