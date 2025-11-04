<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'title'=>$this->title,
          'cell_phone'=>$this->cell_phone,
            'address'=>$this->address,
            'Province_id'=>$this->province_id,
            'Province_title'=>$this->whenLoaded('Province')->name,
            'city_id'=>$this->city_id,
            'city_title'=>$this->whenLoaded('City')->name
        ];
    }
}
