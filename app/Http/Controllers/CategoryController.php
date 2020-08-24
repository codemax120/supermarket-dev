<?php

namespace App\Http\Controllers;

use App\Category;
use App\Helpers\AppHelper;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
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
        $categories = Category::paginate();

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado las categorias', 'Informacion listada exitosamente',
            $request->ip());

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        Gate::authorize('action', 'users');
        $category = Category::create([
            'category_name' => $request->input('name'),
        ]);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha registrado las categoria ' . $category->category_name,
            'Informacion registrada exitosamente',
            $request->ip());


        return response(new CategoryResource($category), Response::HTTP_CREATED);
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
        $category = Category::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado la categoria ' . $category->category_name,
            'Informacion listada exitosamente',
            $request->ip());

        return new CategoryResource($category);
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
        $category = Category::find($id);
        $category->update([
            'category_name' => $request->input('name'),
        ]);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha actualizado la categoria ' . $category->category_name,
            'Informacion listada exitosamente',
            $request->ip());

        return response(new CategoryResource($category), Response::HTTP_ACCEPTED);
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

        $category = Category::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha eliminado la categoria ' . $category->category_name,
            'Informacion eliminada exitosamente',
            $request->ip());

        Category::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
