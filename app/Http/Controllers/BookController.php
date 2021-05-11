<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index(){
        $model = new BookModel;
        
        $data = $model->get();

        return response($data, $data['code']);
    }

    public function shows(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new BookModel;

        $where = [
            'id' => $json['id']
        ];

        $return_value = $model->put($where);

        return response($return_value, $return_value['code']);
    }

    public function create(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new BookModel;

        $data = [
            'number' => $json['txtinput[71]'],
            'title' => $json['txtinput[72]'],
            'author' => $json['txtinput[73]'],
            'genre__id' => $json['txtinput[74]'],
            'release_year' => $json['txtinput[75]'],
            'description' => $json['txtinput[76]'],
            'created_by' => $json['txtinput[77]'],
            'created_date' => date('Y-m-d H:i:s')
        ];

        $return_value = $model->insert($data);

        return response($return_value, $return_value['code']);
    }

    public function update(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new BookModel;

        $data = [
            'number' => $json['txtinput[71]'],
            'title' => $json['txtinput[72]'],
            'author' => $json['txtinput[73]'],
            'genre__id' => $json['txtinput[74]'],
            'release_year' => $json['txtinput[75]'],
            'description' => $json['txtinput[76]'],
            'modified_by' => $json['txtinput[77]'],
            'modified_date' => date('Y-m-d H:i:s')
        ];

        $return_value = $model->insert($data, array('id' => $json['txtinput[70]']));

        return response($return_value, $return_value['code']);
    }

    public function delete(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new BookModel;

        $where = [
            'id' => $json['id']
        ];

        $return_value = $model->delete($where);

        return response($return_value, $return_value['code']);
    }
}
