<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // https://laravel.com/docs/5.5/validation
        $request->validate([
            'name' => 'required|string|min:5|max:20',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|confirmed|string|min:6|max:32',
        ]);

        // https://laravel.com/docs/5.5/eloquent
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // https://laravel.com/docs/5.5/responses#json-responses
        return response()->json($user);
    }

    public function login(Request $request)
    {
        // https://laravel.com/docs/5.5/validation
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6|max:32',
        ]);

        // https://laravel.com/docs/5.5/authentication
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException('Email atau password anda salah!');
        }

        // https://laravel.com/docs/5.5/eloquent-resources
        return (new UserResource(Auth::user()))->additional([
            'meta' => [
                'api_token' => Auth::user()->createToken(env('APP_KEY'))->accessToken, # https://laravel.com/docs/5.5/passport
            ],
        ]);
    }
}
