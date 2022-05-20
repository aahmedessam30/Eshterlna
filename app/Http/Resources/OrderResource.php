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
            'items'           => OrderItemResource::collection($this->items),
            'payment_method'  => $this->when(!is_null($this->payment_method_id), new PaymentMethodResource($this->paymentMethod)),
            'shipping_method' => $this->when(!is_null($this->shipping_method_id), new ShippingMethodResource($this->shippingMethod)),
            'status'          => $this->status,
            'total_price'     => $this->total_price,
            'total_discount'  => $this->total_discount,
            'total_tax'       => $this->total_tax,
            'total_shipping'  => $this->total_shipping,
            'total_weight'    => $this->total_weight,
            'total_items'     => $this->total_items,
            $this->mergeWhen(!is_null($this->ordered_at), [
                'ordered_at'   => $this->ordered_at,
            ]),
            $this->mergeWhen(!is_null($this->shipped_at), [
                'shipped_at'   => $this->shipped_at,
            ]),
            $this->mergeWhen(!is_null($this->cancelled_at), [
                'cancelled_at' => $this->cancelled_at,
            ]),
            $this->mergeWhen(!is_null($this->completed_at), [
                'completed_at' => $this->completed_at,
            ]),
            'created_at'      => $this->created_at->diffForHumans(),
            'updated_at'      => $this->updated_at->diffForHumans(),
        ];
    }
}
