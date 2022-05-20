<?php

namespace App\Http\Resources;

use App\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'id'         => $this->pivot->item_id,
            'name'       => Item::find($this->pivot->item_id)->name,
            'quantity'   => $this->pivot->quantity,
            'price'      => $this->pivot->price,
            'discount'   => $this->pivot->discount,
            'tax'        => $this->pivot->tax,
            'shipping'   => $this->pivot->shipping,
            'weight'     => $this->pivot->weight,
            'created_at' => $this->pivot->created_at->diffForHumans(),
            'updated_at' => $this->pivot->updated_at->diffForHumans(),
        ];
    }
}
