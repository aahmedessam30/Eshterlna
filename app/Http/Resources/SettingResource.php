<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'id'       => $this->id,
            'currency' => $this->currency,
            'terms'    => $this->terms,
            'about'    => $this->about,
            'theme'    => $this->theme,
            'image'    => $this->image,
            'color'    => $this->color,
            'size'     => $this->size,
            'store'    => $this->store,
            'delivery' => $this->delivery,
            'payment'  => $this->payment,
        ];
    }
}
