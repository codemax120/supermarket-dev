<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupermarketStoreRequest;
use App\Http\Requests\SupermarketUpdateImageRequest;
use App\Http\Resources\SupermarketResource;
use App\Supermarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\s;

class SupermarketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supermarkets = Supermarket::paginate();
        return SupermarketResource::collection($supermarkets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupermarketStoreRequest $request)
    {
        $logo = $request->file('logo');
        $logo_name = '';
        if ($logo) {
            $logo_name .= time() . '-' . $logo->getClientOriginalName();
            Storage::disk('logo')->put($logo_name, File::get($logo));
        }

        $supermarket = Supermarket::create([
            'supermarket_name' => $request->input('name'),
            'supermarket_logo' => $logo_name,
            'supermarket_status' => 1,
        ]);
        return response(new SupermarketResource($supermarket), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supermarket = Supermarket::find($id);
        return new SupermarketResource($supermarket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $supermarket = Supermarket::find($id);
        $supermarket->update([
            'supermarket_name' => $request->input('name'),
            'supermarket_status' => $request->input('status'),
        ]);
        return response(new SupermarketResource($supermarket), Response::HTTP_ACCEPTED);
    }

    public function logoUpdate(SupermarketUpdateImageRequest $request)
    {
        $supermarket = Supermarket::find($request->id);
        $logo = $request->file('logo');
        $logo_name = '';
        if ($logo) {
            Storage::disk('logo')->delete($supermarket->supermarket_logo);
            $logo_name .= time() . '-' . $logo->getClientOriginalName();
            Storage::disk('logo')->put($logo_name, File::get($logo));
        }
        $supermarket->update([
            'supermarket_logo' => $logo_name,
        ]);
        return response(new SupermarketResource($supermarket), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
