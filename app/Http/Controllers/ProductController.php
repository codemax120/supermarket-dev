<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateImageRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Product::paginate();
        return ProductResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $product_image = $request->file('image');
        $product_image_name = '';
        if ($product_image) {
            $product_image_name .= time() . '-' . $product_image->getClientOriginalName();
            Storage::disk('product')->put($product_image_name, File::get($product_image));
        }
        $date = $this->formatDate($request->input('due_date'));
        $product = new Product();
        $product->product_name = $request->input('name');
        $product->product_price = $request->input('price');
        $product->product_image = $product_image_name;
        $product->product_due_date = $date;
        $product->product_weight = $request->input('weight');
        $product->product_perishable = $request->input('perishable');
        $product->category_id = $request->category_id;
        $product->save();
        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    public function formatDate($date)
    {
        $valueDate = explode('/', $date);
        return $valueDate[2] . '-' . $valueDate[1] . '-' . $valueDate[0];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $date = $this->formatDate($request->input('due_date'));
        $product = Product::find($id);
        $product->product_name = $request->input('name');
        $product->product_price = $request->input('price');
        $product->product_due_date = $date;
        $product->product_weight = $request->input('weight');
        $product->product_perishable = $request->input('perishable');
        $product->category_id = $request->category_id;
        $product->update();
        return response(new ProductResource($product), Response::HTTP_ACCEPTED);
    }

    public function imageProductUpdate(ProductUpdateImageRequest $request)
    {
        $product = Product::find($request->id);
        $product_image = $request->file('image');
        $product_image_name = '';
        if ($product_image) {
            Storage::disk('product')->delete($product->product_image);
            $product_image_name .= time() . '-' . $product_image->getClientOriginalName();
            Storage::disk('product')->put($product_image_name, File::get($product_image));
        }
        $product->update([
            'product_image' => $product_image_name,
        ]);
        return response(new ProductResource($product), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        Storage::disk('product')->delete($product->product_image);
        $product->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
