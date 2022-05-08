<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'image'        => $this->image,
            'pay_price'    => $this->pay_price,
            'sale_price'   => $this->sale_price,
            'lowest_price' => $this->lowest_price,
            'discount'     => $this->discount,
            'code'         => $this->code,
            'online'       => $this->online,
            'brand_id'     => $this->brand_id,
            'created_at'   => $this->created_at->diffForHumans(),
            'updated_at'   => $this->updated_at->diffForHumans(),
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'brand'        => new BrandResource($this->whenLoaded('brand')),
            'colors'       => ColorResource::collection($this->whenLoaded('colors')),
            'sizes'        => SizeResource::collection($this->whenLoaded('sizes')),
            'stores'       => StoreResource::collection($this->whenLoaded('stores')),
            'merchant'     => new UserResource($this->whenLoaded('merchant')),
        ];
    }
}
