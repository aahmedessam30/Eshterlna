<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicResource extends JsonResource
{
    public $status, $data, $key;

    public function __construct($status, $data, $key = 'data')
    {
        $this->key     = $key;
        $this->data    = $data;
        $this->status  = $status;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status'    => $this->status,
            $this->key  => $this->data,
        ];
    }
}
