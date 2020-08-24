<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateImageRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public $appLog;

    function __construct()
    {
        $this->appLog = new AppHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('action', 'users');
        $categories = Product::paginate();

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado los productos', 'Informacion listada exitosamente',
            $request->ip());

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
        Gate::authorize('action', 'users');
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

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha registrado un nuevo producto ' . $product->product_name,
            'Informacion registrada exitosamente',
            $request->ip());

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
    public function show(Request $request, $id)
    {
        Gate::authorize('action', 'users');
        $product = Product::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado la informacion de un producto ' . $product->product_name,
            'Informacion listada exitosamente',
            $request->ip());

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
        Gate::authorize('action', 'users');
        $date = $this->formatDate($request->input('due_date'));
        $product = Product::find($id);
        $product->product_name = $request->input('name');
        $product->product_price = $request->input('price');
        $product->product_due_date = $date;
        $product->product_weight = $request->input('weight');
        $product->product_perishable = $request->input('perishable');
        $product->category_id = $request->category_id;
        $product->update();

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha actualizado el producto ' . $product->product_name,
            'Informacion actualizada exitosamente',
            $request->ip());

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
    public function destroy(Request $request, $id)
    {
        Gate::authorize('action', 'users');
        $product = Product::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha elimnado el producto ' . $product->product_name,
            'Informacion eliminada exitosamente',
            $request->ip());

        Storage::disk('product')->delete($product->product_image);
        $product->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
