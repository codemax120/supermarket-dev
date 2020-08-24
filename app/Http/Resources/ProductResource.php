<?php

namespace App\Http\Resources;

use App\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $this->getValues($this->category_id);
        return [
            'id' => $this->id,
            'name' => $this->product_name,
            'price' => $this->product_price,
            'image' => ($this->product_image == '') ? '' : public_path('assets/products/' . $this->product_image),
            'due_date' => $this->product_due_date,
            'weight' => $this->product_weight,
            'perishable' => $this->product_perishable,
            'category' => [
                'id' => $this->category_id,
                'name' => $this->getValues($this->category_id)
            ]
        ];
    }

    public function getValues($id)
    {
        $value = Category::where('id', $id)->first();
        return $value->category_name;
    }
}
