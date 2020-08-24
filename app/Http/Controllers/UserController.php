<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
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
     * @QA\Get(path="/users"
     *     @QA\Response(response="200"
     *         descripcion="User Collection"
     *     )
     * )
     */
    public function index(Request $request)
    {
        Gate::authorize('action', 'users');
        $users = User::paginate();
        $this->appLog->setLogs(Auth::id(), Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha listado los usuarios', 'Sesion iniciada exitosamente', $request->ip());
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        Gate::authorize('action', 'users');
        $supermarket_id = $request->input('supermarket_id');
        $supermarket_branch_id = $request->input('supermarket_branch_id');

        if (($supermarket_id == 0 || $supermarket_id == '') && ($supermarket_branch_id == 0 || $supermarket_branch_id == '')) {
            return response('Debe de ingresar si estara acargo de un supermercado o sucursal', Response::HTTP_CONFLICT);
        }

        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id')
        ]);

        if ($supermarket_id != 0) {
            if ($user->role_id == 2) {
                DB::table('users_supermarket')->insert([
                    'user_id' => $user->id,
                    'supermarket_id' => $supermarket_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        if ($supermarket_branch_id != 0) {
            if ($user->role_id == 3) {
                DB::table('users_supermarket_branch')->insert([
                    'user_id' => $user->id,
                    'supermarket_branch_id' => $request->input('supermarket_branch_id'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha registrado a ' . $user->first_name . ' ' . $user->last_name,
            'Registro de nuevo usuario realizado exitosamente', $request->ip());

        return response(new UserResource($user), Response::HTTP_CREATED);
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
        $user = User::find($id);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha visto el detalle de informacion de ' . $user->first_name . ' ' . $user->last_name,
            'Detalle confirmado exitosamente', $request->ip());

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        Gate::authorize('action', 'users');

        $user = User::find($id);
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
        ]);

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha actualizado la informacion de  ' . $user->first_name . ' ' . $user->last_name,
            'Actualizacion realizada exitosamente', $request->ip());

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
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
        $user = User::find($id);

        DB::table('users_supermarket')->where('user_id', $user->id)->delete();
        DB::table('users_supermarket_branch')->where('user_id', $user->id)->delete();

        $this->appLog->setLogs(Auth::id(),
            Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha eliminado el usuario  ' . $user->first_name . ' ' . $user->last_name,
            'Eliminacion realizada exitosamente', $request->ip());
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function user(Request $request)
    {
        $user = Auth::user();

        $this->appLog->setLogs($user->id,
            $user->first_name . ' ' . $user->last_name . ' , ha visto su informacion personal',
            'Informacion mostrada exitosamente', $request->ip());

        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = Auth::user();

        $this->appLog->setLogs($user->id,
            $user->first_name . ' ' . $user->last_name . ' , ha actualizado su informacion personal',
            'Informacion actualizada exitosamente', $request->ip());

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        $this->appLog->setLogs($user->id,
            $user->first_name . ' ' . $user->last_name . ' , ha actualizado actualizado su contraseña',
            'Contraseña actualizada exitosamente', $request->ip());

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

}
