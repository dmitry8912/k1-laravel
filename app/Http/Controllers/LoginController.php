<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        Log::info("new login attempt ${credentials["email"]} | START");
        if(Auth::attempt($credentials)) {
            Log::info("new login attempt ${credentials["email"]} success, found in database, return 200 & token");
            $user = User::where('email',$credentials['email'])->first();
            $token = $user->createToken('k1-auth-token');
            return response([
                'apiToken' => $token->plainTextToken,
                'userId' => $user->id,
                'userName' => $user->email
            ], 200);
        } else {
            Log::info("new login attempt ${credentials["email"]} fail");
            $s = '';
            $auth = base64_encode($credentials['email'] . ':' . $credentials['password']);
            $context = stream_context_create([
                'http' => [
                    'header' => [
                        "Authorization: Basic $auth"
                    ]
                ]
            ]);

            try {
                Log::info("trying to check Gitea for ${credentials["email"]}");
                $s = file_get_contents(config('k1c.gitea_url').'api/v1/user', false, $context);
            } catch (\Exception $ex) {
                Log::info("trying to check Gitea for ${credentials["email"]} => exception, return 401");
                return response([
                    'error' => 'Wrong login name or password or service is not available'
                ], 401);
            }
            Log::info("trying to check Gitea for ${credentials["email"]} => ok, searching in DB");
            if(User::where('email',$credentials['email'])->count() == 0) {
                Log::info("DB search not found ${credentials["email"]} => creating new user | END ");
                $user = new User();
                $user->email = $credentials['email'];
                $user->name = $credentials['email'];
                $user->password = Hash::make($credentials['password']);
                $user->save();
                $token = $user->createToken('k1-auth-token');
                return response([
                    'apiToken' => $token->plainTextToken,
                    'userId' => $user->id,
                    'userName' => $user->email
                ], 200);
            } else {
                Log::info("DB search found ${credentials["email"]} => changing password | END ");
                $user = User::where('email',$credentials['email'])->first();
                $user->password = Hash::make($credentials['password']);
                $user->save();
                $token = $user->createToken('k1-auth-token');
                return response([
                    'apiToken' => $token->plainTextToken,
                    'userId' => $user->id,
                    'userName' => $user->email
                ], 200);
            }
        }
    }
}
