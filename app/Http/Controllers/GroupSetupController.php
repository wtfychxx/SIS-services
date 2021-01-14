<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupSetupModel;
use Illuminate\Support\Facades\DB;

class GroupSetupController extends Controller
{
    //

    public function index(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new GroupSetupModel;

        $return_value = $model->put($json['school_id']);

        return response($return_value, $return_value['code']);
    }

    public function shows(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new GroupSetupModel;

        $return_value = $model->put($json['school_id'], array('id' => $json['id']));

        return response($return_value, $return_value['code']);
    }

    public function create(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new GroupSetupModel;

        
    }
}
