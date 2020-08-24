<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupermarketBranchStore;
use App\Http\Requests\SupermarketBranchUpdateRequest;
use App\Http\Resources\SupermarketBranchResource;
use App\SupermarketBranch;
use App\SupermarketBranchBridge;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SupermarketBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('action', 'users');
        $branchs = SupermarketBranch::paginate();
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
        return response(new SupermarketBranchResource($branch), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Gate::authorize('action', 'users');
        $branch = SupermarketBranch::find($id);
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
        return response(new SupermarketBranchResource($branch), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('action', 'users');
        $branch = SupermarketBranch::find($id);
        SupermarketBranchBridge::where('supermarket_branch_id', $branch->id)->delete();
        $branch->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
