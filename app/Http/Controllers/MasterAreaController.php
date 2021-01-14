<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterAreaModel;
use Illuminate\Support\Facades\DB;

class MasterAreaController extends Controller
{
    public function index(){
        $model = new MasterAreaModel;

        $return_value = $model->getAllData();

        return response($return_value, 200);
    }

    public function shows(Request $request){
        $json = json_decode($request->getContent(), true);
        $id = $json['id'];

        $model = new MasterAreaModel;

        $return_value = $model->getModalData($id);

        return response($return_value, 200);
    }

    public function create(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterAreaModel;

        $data = [
            'parent_id' => $json['txtinput[71]'],
            'name' => $json['txtinput[72]'],
            'area_type__id' => $json['txtinput[73]'],
            'postcode' => trim($json['txtinput[74]']) == '' ? null : $json['txtinput[74]']
        ];

        $where = trim($json['txtinput[70]']) == '' ? array() : array('id' => $json['txtinput[70]']);

        $return_value = $model->insert($data, $where);

        return response($return_value, $return_value['code']);
    }

    public function delete(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterAreaModel;

        $return_value = $model->delete(array('id' => $json['id']));

        return response($return_value, $return_value['code']);
    }
}
