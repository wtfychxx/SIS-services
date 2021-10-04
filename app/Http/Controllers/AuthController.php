<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){
        $validate = \Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()){
            $respon = [
                'status' => 'errors',
                'message' => 'Validator Error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($respon, 200);
        }else{
            $credentials = request(['email', 'password']);
            $credentials = Arr::add($credentials, 'status', 'active');
            if (!Auth::attempt($credentials)) {
                $respon = [
                    'status' => 'error',
                    'message' => 'Unathorized',
                    'errors' => null,
                    'content' => null,
                ];
                return response()->json($respon, 401);
            }

            $user = User::where('email', $request->email)->first();
            if (! \Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            $tokenResult = $user->createToken('token-auth')->plainTextToken;
            $respon = [
                'status' => 'success',
                'message' => 'Login successfully',
                'errors' => null,
                'content' => [
                    'status_code' => 200,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ]
            ];
            return response()->json($respon, 200);
        }
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $respon = [
            'status' => 'success',
            'message' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }

    public function logoutall(Request $request) {
        $user = $request->user();
        $user->tokens()->delete();
        $respon = [
            'status' => 'success',
            'message' => 'Logout successfully',
            'errors' => null,
            'content' => null,
        ];
        return response()->json($respon, 200);
    }
}
