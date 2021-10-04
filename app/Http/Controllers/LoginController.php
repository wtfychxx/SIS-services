<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginModel;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller{
    public function check(Request $request){
        $json = json_decode($request->getContent(), true);

        if($json['email'] === 'fadhliyulyanto@gmail.com' && $json['password'] === '123123123'){
            $return_value = ['status' => 'success', 'message' => 'Successfully Logged In', 'status' => 200];
        }else{
            $return_value = ['status' => 'success', 'message' => 'Email and password not match!', 'status' => 500];
        }

        return response($return_value, $return_value['status']);
    }
}

?>