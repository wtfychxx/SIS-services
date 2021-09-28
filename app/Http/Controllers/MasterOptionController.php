<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterOptionModel;
use Illuminate\Support\Facades\DB;

class MasterOptionController extends Controller
{
    //
    public function combo_fill(Request $request){
        $json = json_decode($request->getContent(), true);

        $type = $json['type'];
        $where = (isset($json['where'])) ? $json['where'] : '';
        $is_array = (isset($json['isarray'])) ? $json['isarray'] : FALSE;
        $model = new MasterOptionModel;

        switch($type){
            case 'religion':
                $data = $model->get_combobox('db', 1, $where, $is_array);
            break;

            case 'marital_status':
                $data = $model->get_combobox('db', 2, $where, $is_array);
            break;

            case 'salutation':
                $data = $model->get_combobox('db', 3, $where, $is_array);
            break;

            case 'family_relationship':
                $data = $model->get_combobox('db', 4, $where, $is_array);
            break;

            case 'occupation':
                $data = $model->get_combobox('db', 5, $where, $is_array);
            break;

            case 'area_type':
                $data = $model->get_combobox('db', 6, $where, $is_array);
            break;

            case 'facility':
                $data = $model->get_combobox('db', 7, $where, $is_array);
            break;

            case 'education':
                $data = $model->get_combobox('db', 8, $where, $is_array);
            break;

            case 'genre':
                $data = $model->get_combobox('db', 10, $where, $is_array);
            break;

            case 'majors':
                $data = $model->get_combobox('db', 11, $where, $is_array);
            break;

            case 'book':
                $data = $model->get_combobox('db', 'ci_sis_library_master_book', $where, $is_array);
            break;

            case 'language':
                $data = $model->get_combobox('db', 'ci_system_language', $where, $is_array);
            break;

            case 'area':
                $data = $model->get_combobox('db', 'ci_sis_master_area', $where, $is_array);
            break;

            case 'country':
                $data = $model->get_combobox('db', 'ci_sis_master_area', array('area_type__id' => 14));
            break;

            case 'province':
                $data = $model->get_combobox('db', 'ci_sis_master_area', array('parent_id' => $json['parent']));
            break;

            case 'city':
                $data = $model->get_combobox('db', 'ci_sis_master_area', array('parent_id' => $json['parent']));
            break;

            case 'school_parent':
                $data = $model->get_combobox('db', 'ci_sis_organization_school');
            break;

            case 'school':
                $data = $model->get_combobox('db', 'ci_sis_organization_school');
            break;

            case 'auth_type':
                $data = $model->get_combobox('db', 13);
            break;

            case 'status':
                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'result' => [
                        'Active' => 'Active',
                        'Inactive' => 'Inactive'
                    ]
                ];
            break;

            case 'boolean':
                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'result' => [
                        'TRUE' => 'TRUE',
                        'FALSE' => 'FALSE'
                    ]
                ];
            break;
        }

        return response($data);
    }

    public function generate(Request $request){
        $json = json_decode($request->getContent(), true);

        $model = new MasterOptionModel;

        $return_value = $model->generate($json['type'], $json['table']);

        return response($return_value, $return_value['code']);
    }
}
