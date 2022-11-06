<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth, Hash, Validator, Str;

class LoginController extends Controller
{
    public function login(Request $request)
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

            if($user->is_admin)
            {
                $token = $user->createToken(Str::random(60))->accessToken;                
                $data['user'] = $user;
                $data['access_token'] = $token;
                return response()->json($data);
            }
            else{
                $data['invalid'] = 'You are not allowed!';
                return response()->json(['errors' => $data ], 422);
            }

        }
        else{
            $data['invalid'] = 'Invalid credentials!';
            return response()->json(['errors' => $data ], 422);
        }
    }

    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->json(['message' => 'Logout Success']);
    }
}
