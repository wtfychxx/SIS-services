<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolModel;

class SchoolController extends Controller
{
    //
    public function index(){
        $model = new SchoolModel;

        $data = $model->dataPut();

        return response($data, $data['code']);
    }

    public function shows(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new SchoolModel;

        $return_value = $model->dataPut(array('id' => $json['id']));

        return response($return_value, $return_value['code']);
    }

    public function create(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new SchoolModel;

        $data = [
            'parent_id' => $json['txtinput[1]'],
            'code' => $json['txtinput[2]'],
            'name' => $json['txtinput[3]'],
            'name_official' => $json['txtinput[4]'],
            'owner_name' => $json['txtinput[5]'],
            'address' => $json['txtinput[6]'],
            'country__id' => $json['txtinput[7]'],
            'province__id' => $json['txtinput[8]'],
            'city__id' => $json['txtinput[9]'],
            'zipcode' => $json['txtinput[10]'],
            'email' => $json['txtinput[11]'],
            'phone' => $json['txtinput[12]'],
        ];

        $return_value = $model->insert($data);

        return response($return_value, $return_value['code']);
    }

    public function update(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new SchoolModel;

        $data = [
            'parent_id' => $json['txtinput[1]'],
            'code' => $json['txtinput[2]'],
            'name' => $json['txtinput[3]'],
            'name_official' => $json['txtinput[4]'],
            'owner_name' => $json['txtinput[5]'],
            'address' => $json['txtinput[6]'],
            'country__id' => $json['txtinput[7]'],
            'province__id' => $json['txtinput[8]'],
            'city__id' => $json['txtinput[9]'],
            'zipcode' => $json['txtinput[10]'],
            'email' => $json['txtinput[11]'],
            'phone' => $json['txtinput[12]'],
        ];

        $where = [
            'id' => $json['txtinput[0]']
        ];

        $return_value = $model->insert($data, $where);

        return response($return_value, $return_value['code']);
    }

    public function delete(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new SchoolModel;

        $where = [
            'id' => $json['id']
        ];

        $return_value = $model->delete($where);

        return response($return_value, $return_value['code']);
    }
}
