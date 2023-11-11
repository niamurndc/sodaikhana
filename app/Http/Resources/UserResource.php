<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'ref_code' => $this->ref_code,
            'point' => $this->point,
            'balance' => $this->balance,
            'phone' => $this->phone,
            'area' => $this->area,
            'address' => $this->address,
            'status' => $this->status,
            'role' => $this->role,
            'created' => $this->created_at->format('d M Y'),
            'id' => $this->id,
        ];
    }
}
