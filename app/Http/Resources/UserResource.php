<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email'      => $this->email,
            'phone'      => $this->phone,
            'username'   => $this->username,
            'image'      => $this->image,
            'device_key' => $this->device_key,
            'address'    => $this->whenNotNull($this->address , 'لا يوجد عنوان'),
            'type'       => $this->type,
            'status'     => $this->status,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'country'    => $this->when($this->country_id, new CountryResource($this->country), 'لا يوجد دولة'),
            'city'       => $this->when($this->city_id, new CityResource($this->city), 'لا يوجد مدينة'),
        ];
    }
}
