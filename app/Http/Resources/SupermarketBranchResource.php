<?php

namespace App\Http\Resources;

use App\Supermarket;
use App\SupermarketBranchBridge;
use Illuminate\Http\Resources\Json\JsonResource;

class SupermarketBranchResource extends JsonResource
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
            'supermarket_id' => $this->id,
            'supermarket_branch_name' => $this->supermarket_branch_name,
            'supermarket_branch_address' => $this->supermarket_branch_address,
            'supermarket_branch_status' => $this->supermarket_branch_status,
            'supermarket' => [
                'supermarket_name' => $this->getValueFromId($this->id)
            ]
        ];
    }

    public function getValueFromId($id)
    {
        $bridge = SupermarketBranchBridge::select('supermarket_id')->where('supermarket_branch_id', $id)->first();
        $value = Supermarket::select('id', 'supermarket_name', 'supermarket_logo', 'supermarket_status')->where('id', $bridge->supermarket_id)->first();
        return [
            'id' => $value->id,
            'name' => $value->supermarket_name,
            'logo' => ($value->supermarket_logo == '') ? '' : public_path('assets/logos/' . $value->supermarket_logo),
            'status' => $value->supermarket_status
        ];
    }

}
