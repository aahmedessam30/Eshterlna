<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'address'    => $this->address,
            'phone'      => $this->phone,
            'email'      => $this->email,
            'image'      => $this->image,
            'latitude'   => $this->lat,
            'longitude'  => $this->lng,
            'online'     => $this->online,
            'quantity'   => $this->whenPivotLoaded('item_store', function () {
                return $this->pivot->quantity;
            }),
            'items_count'=> $this->items->count(),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'city'       => $this->whenNotNull(new CityResource($this->city)),
            'merchant'   => $this->whenNotNull(new UserResource($this->merchant))
        ];
    }
}
