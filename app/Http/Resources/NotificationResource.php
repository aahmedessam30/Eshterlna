<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id'          => $this->id,
            'message'     => $this->data['message'],
            'read'        => $this->read_at != null ? true : false,
            $this->mergeWhen($this->read_at != null, [
                'read_at' => $this->read_at ? $this->read_at->diffForHumans() : null,
            ]),
            'created_at'  => $this->created_at->diffForHumans(),
            'updated_at'  => $this->updated_at->diffForHumans(),
        ];
    }
}
