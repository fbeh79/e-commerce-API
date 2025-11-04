<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransActionResource extends JsonResource
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
            'status'=>$this->status,
            'amount'=>$this->amount,
            'ref_number'=>$this->ref_number,
            'created_at'=>verta($this->id)->format('Y-m-d'),
        ];
    }
}
