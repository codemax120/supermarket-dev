<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public $appLog;

    function __construct()
    {
        $this->appLog = new AppHelper();
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;
            $this->appLog->setLogs(Auth::id(), Auth::user()->first_name . ' ' . Auth::user()->last_name . ' , ha iniciado sesion', 'Sesion iniciada exitosamente', $request->ip());
            return [
                'token' => $token
            ];
        }
        return response([
            'error' => 'Invalid Credentials!'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => 1
        ]);
        $this->appLog->setLogs(0, $user->first_name . ' ' . $user->last_name . ' , se ha registrado exitosamente', 'Registro realizado exitosamente', $request->ip());
        return response($user, Response::HTTP_CREATED);
    }
}
