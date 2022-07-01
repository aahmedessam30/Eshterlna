<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'image'       => $this->image,
            'status'      => $this->category_id,
            'online'      => $this->online,
            'created_at'  => $this->created_at->diffForHumans(),
            'updated_at'  => $this->updated_at->diffForHumans(),
            'category'    => CategoryResource::collection($this->whenLoaded('subCategories')),
            'items'       => ItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
