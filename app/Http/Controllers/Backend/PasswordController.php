<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\EmailVerification;
use Validator, Hash, Auth, Notification, Str, DB;

class PasswordController extends Controller
{
    public function EmailVerify(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => ['required', 'exists:users']
        ]);

        if($validated->fails())
        {
            return response()->json(['errors' => $validated->errors()],422);
        }

        $user = User::whereEmail($request->email)->first();
        $token = Str::random(60);
        Notification::send($user, new EmailVerification($token));
        DB::table('password_resets')
            ->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()->addHours(1),
            ]);
        
        return response()->json(['message' => 'Password reset link sent!']);
        


    }

    public function ResetPassword(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'token' => ['required', 'exists:password_resets'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        if($validated->fails())
        {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $user_token = DB::table('password_resets')
                ->where('token', $request->token)
                ->first();

        if($user_token->created_at > now())
        {
            $updated = User::whereEmail($user_token->email)
            ->update([
                'password' => Hash::make($request->password)
            ]);
            if($updated)
            {
                return response()->json(['message' => 'Password reset successfully!']);
            }
        }
        else{
            return response()->json(['message' => 'Token has been expired!'], 422);
        }
       
    }
}
