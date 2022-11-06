<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth, Hash, Validator, Str;

class UserAuthController extends Controller
{
    public function UserLogin(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'email' => ['required', 'exists:users'],
            'password' => ['required'],
        ]);

        if($validatedData->fails())
        {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        if(Auth::attempt($request->only(['email', 'password'])))
        {
            $user = Auth::user();
            $token = $user->createToken(Str::random(60))->accessToken;                
            $data['user'] = $user;
            $data['access_token'] = $token;
            return response()->json($data,200);
        }
        else{
            return response()->json(['errors' => 'Invalid credentials!']);
        }
    }

    public function UserLogout()
    {
        Auth::user()->token()->revoke();
        return response()->json(['message' => 'Logout Success']);
    }

    public function UserRegister(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'name' => ['required'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if($validatedData->fails())
        {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $data = $request->only('name', 'email');
        $data['password'] = Hash::make($request->password);
        $registered = User::create($data);
        if($registered)
        {
            Auth::login($registered);
            $user = Auth::user();
            $token = $user->createToken(Str::random(60))->accessToken;                
            $data['user'] = $user;
            $data['access_token'] = $token;
            return response()->json($data,200);
        }

    }
}
