<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'user'            => $this->when(!is_null($this->user_id), new UserResource($this->user)),
            'store'           => $this->when(!is_null($this->store_id), new StoreResource($this->store)),
            'payment_method'  => $this->when(!is_null($this->payment_method_id), new PaymentMethodResource($this->paymentMethod)),
            'shipping_method' => $this->when(!is_null($this->shipping_method_id), new ShippingMethodResource($this->shippingMethod)),
            'status'          => $this->status,
            'total_price'     => $this->total_price,
            'total_discount'  => $this->total_discount,
            'total_tax'       => $this->total_tax,
            'total_shipping'  => $this->total_shipping,
            'total_weight'    => $this->total_weight,
            'total_items'     => $this->total_items,
            'quantity'        => $this->whenPivotLoaded('order_items', function () {
                return $this->pivot->quantity;
            }),
            'price'           => $this->whenPivotLoaded('order_items', function () {
                return $this->pivot->price;
            }),
            'discount'        => $this->whenPivotLoaded('order_items', function () {
                return $this->pivot->discount;
            }),
            'tax'             => $this->whenPivotLoaded('order_items', function () {
                return $this->pivot->tax;
            }),
            'shipping'        => $this->whenPivotLoaded('order_items', function () {
                return $this->pivot->shipping;
            }),
            'weight'          => $this->whenPivotLoaded('order_items', function () {
                return $this->pivot->weight;
            }),
            'ordered_at'      => $this->ordered_at->diffForHumans(),
            'shipped_at'      => $this->shipped_at->diffForHumans(),
            'cancelled_at'    => $this->cancelled_at->diffForHumans(),
            'completed_at'    => $this->completed_at->diffForHumans(),
            'created_at'      => $this->created_at->diffForHumans(),
            'updated_at'      => $this->updated_at->diffForHumans(),
        ];
    }
}
