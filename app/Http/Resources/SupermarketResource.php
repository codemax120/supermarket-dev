<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupermarketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'supermarket_name' => $this->supermarket_name,
            'supermarket_logo' => ($this->supermarket_logo == '') ? '' : public_path('assets/logos/' . $this->supermarket_logo),
            'supermarket_status' => $this->supermarket_status
        ];
    }
}
