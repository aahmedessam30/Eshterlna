<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuhtResource extends JsonResource
{
    public $status, $user, $token, $message;

    public function __construct($status, $user, $token, $message)
    {
        $this->status  = $status;
        $this->user    = $user;
        $this->token   = $token;
        $this->message = $message;
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
            'status'  => $this->status,
            'message' => $this->message,
            'token'   => $this->token,
            'data'    => $this->user,
        ];
    }
}
