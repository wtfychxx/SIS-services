<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDataModel;
use Illuminate\Support\Facades\DB;

class MasterDataController extends Controller
{
    public function __construct(){

        $model = new MasterDataModel;
    }

    public function index(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterDataModel;

        $data = $model->dataPut($json);

        return response($data, $data['code']);
    }

    public function get_combo(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        
        $model = new MasterDataModel;
        $result = $model->getData($json);

        if(empty($result)){
            $return_value = response
                            ([
                                'combo_key' => '',
                                'combo_name' => '- choose -'
                            ], 200);
        }else{
            $return_value = response
                            ([
                                $result
                            ], 200);
        }

        return $return_value;
    }

    public function shows(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterDataModel;
        $result = $model->put($json);

        if(empty($result)){
            $return_value = response([
                'status' => 'Error',
                'message' => 'Sorry, data with id '.$json['id'].' not found!'
            ], 200);
        }else{
            $return_value = response([
                'status' => 'success',
                'result' => $result
            ], 200);
        }

        return $return_value;
    }

    public function check(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterDataModel;

        $data = [
            'data__id' => $json['id'],
            'language__id' => $json['language']
        ];

        $return_value = $model->checkAvailable($data);

        return response()->json($return_value);
    }

    public function create(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterDataModel;

        $data = [
            'module_table__id' => $json['module_table_id'],
            'parent_id' => 0,
            'reference_id' => null
        ];

        $insert_header = $model->insert('ci_sis_master_data', $data, $json['txtinput[70]']);

        if($insert_header[0] == 'js_form_insert'){
            $language = DB::table('ci_system_language')->pluck('id');
            foreach($language as $row){    
                $detail = [
                    'data__id' => $insert_header['runid'],
                    'language__id' => $row,
                    'name' => $json['txtinput[72]'],
                    'description' => $json['txtinput[73]']
                ];
                $return_value = $model->insert('ci_sis_master_data_language', $detail);
            }

        }

        return response($return_value, $return_value['code']);
    }

    public function update(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterDataModel;

        $where_check = [
            'data__id' => $json['txtinput[70]'],
            'language__id' => $json['txtinput[71]']
        ];

        $check_available = DB::table('ci_sis_master_data_language')->where($where_check)->count();
        // $check_available = MasterDataModel::firstWhere($where_check);

        if($check_available == 0){
            $data = [
                'data__id' => $json['txtinput[70]'],
                'language__id' => $json['txtinput[71]'],
                'name' => $json['txtinput[72]'],
                'description' => $json['txtinput[73]']
            ];

            $return_value = $model->insert('ci_sis_master_data_language', $data);
        }else{
            $data = [
                'name' => $json['txtinput[72]'],
                'description' => $json['txtinput[73]']
            ];
    
            $where = [
                'data__id' => $json['txtinput[70]'],
                'language__id' => $json['txtinput[71]']
            ];
    
            $return_value = $model->insert('ci_sis_master_data_language', $data, $where);
        }


        return response($return_value, $return_value['code']);
        // return response()->json($return_value);
    }

    public function delete(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterDataModel;

        $where = [
            'data__id' => $json['id'],
            'language__id' => $json['language']
        ];

        $return_value = $model->delete('ci_sis_master_data_language', $where);

        return response($return_value, $return_value['code']);
    }
}
