<?php

namespace App\Http\Resources;

use App\Category;
use App\Product;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
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
            'inventory_id' => $this->id,
            'month' => $this->month,
            'month_order' => $this->month_order,
            'user' => $this->getUser($this->user_id),
            'product_count' => $this->product_count,
            'product' => $this->getProduct($this->product_id),
        ];
    }

    public function getProduct($id)
    {
        $product = Product::find($id);
        return [
            'id' => $product->id,
            'name' => $product->product_name,
            'price' => $product->product_price,
            'image' => ($product->product_image == '') ? '' : public_path('assets/products/' . $product->product_image),
            'due_date' => $this->parseDate($product->product_due_date),
            'weight' => $product->product_weight,
            'perishable' => $product->product_perishable,
            'category' => $this->getCategory($product->category_id)
        ];
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email
        ];
    }

    public function getCategory($id)
    {
        $category = Category::find($id);
        return [
            'id' => $category->id,
            'name' => $category->category_name
        ];
    }

    public function parseDate($date)
    {
        $newDate = explode('-', $date);
        return $newDate[2] . '/' . $newDate[1] . '/' . $newDate[0];
    }

}
