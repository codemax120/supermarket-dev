<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\SupermarketStoreRequest;
use App\Http\Requests\SupermarketUpdateImageRequest;
use App\Http\Resources\SupermarketResource;
use App\Supermarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SupermarketController extends Controller
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
        $supermarkets = Supermarket::paginate();
        $this->appLog->setLogs(Auth::id(), Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado los supermercados', 'Informacion listada exitosamente', $request->ip());
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
        Gate::authorize('action', 'users');
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

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha registrado el super mercado ' . $supermarket->supermarket_name,
            'Informacion registrada exitosamente', $request->ip());

        return response(new SupermarketResource($supermarket), Response::HTTP_CREATED);
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
        $supermarket = Supermarket::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha visto la informacion del supermercado ' . $supermarket->supermarket_name,
            'Informacion listada exitosamente', $request->ip());

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
        Gate::authorize('action', 'users');
        $supermarket = Supermarket::find($id);
        $supermarket->update([
            'supermarket_name' => $request->input('name'),
            'supermarket_status' => $request->input('status'),
        ]);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha actualizado el super mercado ' . $supermarket->supermarket_name,
            'Informacion actualizada exitosamente', $request->ip());

        return response(new SupermarketResource($supermarket), Response::HTTP_ACCEPTED);
    }

    public function logoUpdate(SupermarketUpdateImageRequest $request)
    {
        Gate::authorize('action', 'users');
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
    public function destroy(Request $request, $id)
    {
        Gate::authorize('action', 'users');
        $supermarket = Supermarket::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha eliminado el super mercado ' . $supermarket->supermarket_name,
            'Informacion eliminada exitosamente', $request->ip());

        Storage::disk('logo')->delete($supermarket->supermarket_logo);
        $supermarket->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
