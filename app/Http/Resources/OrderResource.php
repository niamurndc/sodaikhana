<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'area' => $this->area,
            'address' => $this->address,
            'subtotal' => $this->subtotal,
            'paid' => $this->paid,
            'due' => $this->due,
            'status' => $this->status,
            'note' => $this->note,
            'created' => $this->created_at->format('d M Y - h:i'),
            'updated' => $this->updated_at,
            'carts' => CartResource::collection($this->carts),
        ];
    }
}
