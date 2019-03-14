<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Notifications\SignupActivate;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;

class ApiAuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $params = $request->only('email', 'name', 'password');
        $user = new User();
        $user->email = $params['email'];
        $user->name = $params['name'];
        $user->password = bcrypt($params['password']);
        $user->activation_token = str_random(60);
        $user->role = "user";
        $user->save();
        $user->notify(new SignupActivate($user));
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!($token = JWTAuth::attempt($credentials))) {
            return response()->json([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Account or password is incorrect.'
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json(['token' => $token], Response::HTTP_OK);
    }

    public function user(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            return response($user, Response::HTTP_OK);
        }

        return response(null, Response::HTTP_BAD_REQUEST);
    }

    public function refresh()
    {
        return response(JWTAuth::getToken(), Response::HTTP_OK);
    }

    public function registerActivate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();
        return redirect("https://smart-news-nal.herokuapp.com");
    }

    public function registerOrLogin(Request $request)
    {
        $params = $request->only('email', 'name', 'password');
        $pass = $request['password'];
        $user = User::where(['email' => $params['email']])->get();
        // dd($user);
        if(count($user) == 0){
            $user = new User();
            $user->email = $params['email'];
            $user->name = $params['name'];
            $user->password = bcrypt($params['password']);
            $user->role = "employee";
            $user->save();
        }
        $user = User::where(['email' => $params['email']])->get();
        $credentials = ["email" => $user[0]["email"], "password" => $pass];

        // dd($credentials);
        // $token = JWTAuth::attempt($credentials);
        if (!($token = JWTAuth::attempt($credentials))) {
            return response()->json([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Account or password is incorrect.'
            ], Response::HTTP_BAD_REQUEST);
        }
        $user[0]['token'] = $token;
        return response()->json($user, Response::HTTP_OK);
    }
}
