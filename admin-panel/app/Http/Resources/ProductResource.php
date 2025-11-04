<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'category'=>$this->whenLoaded('category'),
            'primary_image'=>url(env('PRODUCT_IMAGE_PATH_URL').$this->primary_image),
            'description'=>$this->description,
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            'date_on_sale_from'=>verta($this->date_on_sale_from)->formatDatetime(),
            'date_on_sale_from_gregorian'=>$this->date_on_sale_from,
            'date_on_sale_to'=>verta($this->date_on_sale_to)->formatDatetime(),
            'date_on_sale_to_gregorian'=>$this->date_on_sale_to,
        ];
    }
}
