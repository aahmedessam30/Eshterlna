<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'review'     => $this->review,
            'rating'     => $this->rating,
            'user'       => $this->when($this->user_id, function () {
                return new UserResource($this->user);
            }),
            'item'       => $this->when($this->item_id, function () {
                return new ItemResource($this->item);
            }),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
