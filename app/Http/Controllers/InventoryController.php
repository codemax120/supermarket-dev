<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\InventoryStoreRequest;
use App\Http\Resources\InventoryResource;
use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
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
        $inventary = Inventory::orderBy('id', 'DESC')->paginate();

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado los inventarios', 'Informacion listada exitosamente',
            $request->ip());

        return InventoryResource::collection($inventary);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(InventoryStoreRequest $request)
    {
        Gate::authorize('action', 'users');
        $inventory = new Inventory();
        $inventory->user_id = Auth::id();
        $inventory->product_id = $request->input('product_id');
        $inventory->product_count = $request->input('product_count');
        $inventory->month = $this->monthOfYear(date('M'))['month'];
        $inventory->month_order = $this->monthOfYear(date('M'))['order'];
        $inventory->save();

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado registrado un nuevo inventario',
            'Informacion registrada exitosamente',
            $request->ip());

        return response(new InventoryResource($inventory), Response::HTTP_CREATED);
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
        $inventory = Inventory::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado la informacion del inventario',
            'Informacion listada exitosamente',
            $request->ip());

        return new InventoryResource($inventory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(InventoryStoreRequest $request, $id)
    {
        Gate::authorize('action', 'users');
        $inventory = Inventory::find($id);
        $inventory->product_id = $request->input('product_id');
        $inventory->product_count = $request->input('product_count');
        $inventory->save();

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha actualizado la informacion del inventario',
            'Informacion actualizada exitosamente',
            $request->ip());

        return response(new InventoryResource($inventory), Response::HTTP_CREATED);
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
        Inventory::destroy($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha eliminado la informacion del inventario',
            'Informacion elimnada exitosamente',
            $request->ip());

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function monthOfYear($month)
    {
        $translate = array();
        switch ($month) {
            case 'Jan':
                $translate = array(
                    "month" => "January",
                    "order" => 1
                );
                break;
            case 'Feb':
                $translate = array(
                    "month" => "February",
                    "order" => 2
                );
                break;
            case 'Mar':
                $translate = array(
                    "month" => "March",
                    "order" => 3
                );
                break;
            case 'Apr':
                $translate = array(
                    "month" => "April",
                    "order" => 4
                );
                break;
            case 'May':
                $translate = array(
                    "month" => "May",
                    "order" => 5
                );
                break;
            case 'Jun':
                $translate = array(
                    "month" => "June",
                    "order" => 6
                );
                break;
            case 'Jul':
                $translate = array(
                    "month" => "July",
                    "order" => 7
                );
                break;
            case 'Aug':
                $translate = array(
                    "month" => "August",
                    "order" => 8
                );
                break;
            case 'Sep':
                $translate = array(
                    "month" => "September",
                    "order" => 9
                );
                break;
            case 'Oct':
                $translate = array(
                    "month" => "October",
                    "order" => 10
                );
                break;
            case 'Nov':
                $translate = array(
                    "month" => "November",
                    "order" => 11
                );
                break;
            case 'Dec':
                $translate = array(
                    "month" => "December",
                    "order" => 12
                );
                break;
        }
        return $translate;
    }
}
