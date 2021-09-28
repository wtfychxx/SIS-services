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

        $return_value = $model->put(array('school__id' => $json['school_id']));

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

        $data = [
            'school__id' => $json['txtinput[70]'],
            'name' => $json['txtinput[71]'],
            'status' => $json['txtinput[72]'],
            'employee_default' => $json['txtinput[73]'],
            'auth_type__id' => $json['txtinput[74]']
        ];

        $menus = $json['txtinput[76]'];

        if(trim($json['txtinput[70]']) === ''){
            $return_value = $model->insert($data, $menus);
        }else{
            $where = [
                'id' => $json['txtinput[70]']
            ];
            $return_value = $model->insert($data, $menus, $where);
        }

        return response($return_value, $return_value['code']);
    }

    public function setup(){
        $model = new GroupSetupModel;

        $return_value = $model->setup();

        return response($return_value, $return_value['code']);
    }

    public function access(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new GroupSetupModel;

        $return_value = $model->getAccess($json['id']);

        return response($return_value, $return_value['code']);
    }
}
