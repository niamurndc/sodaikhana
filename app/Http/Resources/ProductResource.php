<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->image != null ? 'http://sodaikhana.com/uploads/'.$this->image : 'http://sodaikhana.com/uploads/coffee.jpg',
            'desc' => $this->desc,
            'tags' => $this->tags,
            'price' => $this->price,
            'offprice' => $this->offprice,
            'attri' => $this->attri,
            'offer' => $this->offer,
            'popular' => $this->popular,
            'food' => $this->food,
            'stock' => $this->stock_out,
            'qty' => $this->qty,
            'attri1' => $this->attri1,
            'attri2' => $this->attri2,
            'attri3' => $this->attri3,
            'val1' => $this->val1,
            'val2' => $this->val2,
            'val3' => $this->val3,
            'brand' =>  $this->brand_name,
        ];
    }
}