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
        $model = new MasterOptionModel;

        switch($type){
            case 'religion':
                $data = $model->get_combobox('db', 1);
            break;

            case 'marital_status':
                $data = $model->get_combobox('db', 2);
            break;

            case 'salutation':
                $data = $model->get_combobox('db', 3);
            break;

            case 'family_relationship':
                $data = $model->get_combobox('db', 4);
            break;

            case 'occupation':
                $data = $model->get_combobox('db', 5);
            break;

            case 'area_type':
                $data = $model->get_combobox('db', 6);
            break;

            case 'facility':
                $data = $model->get_combobox('db', 7);
            break;

            case 'education':
                $data = $model->get_combobox('db', 8);
            break;

            case 'genre':
                $data = $model->get_combobox('db', 10);
            break;

            case 'majors':
                $data = $model->get_combobox('db', 11);
            break;

            case 'book':
                $data = $model->get_combobox('db', 'ci_sis_library_master_book');
            break;

            case 'language':
                $data = $model->get_combobox('db', 'ci_system_language');
            break;

            case 'area':
                $data = $model->get_combobox('db', 'ci_sis_master_area');
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
