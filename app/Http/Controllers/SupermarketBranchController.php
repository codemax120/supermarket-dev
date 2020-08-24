<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\SupermarketBranchStore;
use App\Http\Requests\SupermarketBranchUpdateRequest;
use App\Http\Resources\SupermarketBranchResource;
use App\SupermarketBranch;
use App\SupermarketBranchBridge;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SupermarketBranchController extends Controller
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
        $branchs = SupermarketBranch::paginate();
        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado las sucursales', 'Informacion listada exitosamente',
            $request->ip());
        return SupermarketBranchResource::collection($branchs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupermarketBranchStore $request)
    {
        Gate::authorize('action', 'users');
        // CREATE THE VALUE
        $branch = SupermarketBranch::create([
            'supermarket_branch_name' => $request->input('name'),
            'supermarket_branch_address' => $request->input('address'),
            'supermarket_branch_status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        // MAKE DE RELATION
        SupermarketBranchBridge::create([
            'supermarket_id' => $request->input('supermarket_id'),
            'supermarket_branch_id' => $branch->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha registrado una nueva sucursal ' . $branch->supermarket_branch_name,
            'Informacion registrada exitosamente', $request->ip());

        return response(new SupermarketBranchResource($branch), Response::HTTP_CREATED);
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
        $branch = SupermarketBranch::find($id);
        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha visto la informacion de la sucursal ' . $branch->supermarket_branch_name,
            'Informacion listada exitosamente', $request->ip());

        return new SupermarketBranchResource($branch);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupermarketBranchUpdateRequest $request, $id)
    {
        Gate::authorize('action', 'users');
        $branch = SupermarketBranch::find($id);
        $branch->update([
            'supermarket_branch_name' => $request->input('name'),
            'supermarket_branch_address' => $request->input('address'),
            'supermarket_branch_status' => $request->input('status'),
            'updated_at' => Carbon::now()
        ]);

        SupermarketBranchBridge::where('supermarket_branch_id', $branch->id)->delete();

        SupermarketBranchBridge::create([
            'supermarket_id' => $request->input('supermarket_id'),
            'supermarket_branch_id' => $branch->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha actualizado la informacion de la sucursal ' . $branch->supermarket_branch_name,
            'Informacion actualizada exitosamente', $request->ip());

        return response(new SupermarketBranchResource($branch), Response::HTTP_ACCEPTED);
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
        $branch = SupermarketBranch::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha eliminado la informacion de la sucursal ' . $branch->supermarket_branch_name,
            'Informacion eliminada exitosamente', $request->ip());

        SupermarketBranchBridge::where('supermarket_branch_id', $branch->id)->delete();
        $branch->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
