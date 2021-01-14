<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class MasterOptionModel extends Model
{
    use HasFactory;

    public function get_combobox($prm_dbase = 'db', $prm_module_table = '', $prm_where = '', $prm_return_array = TRUE){
        try{
            $table = [
                'name' => '',
                'fields' => [
                    'name' => ''
                ],
                'where' => ''
            ];

            if($prm_where != ''){
                $table['where'] = "WHERE ";
                if(is_array($prm_where)){
                    foreach($prm_where as $key => $val){
                        $table['where'] .= " $key='$val' AND";
                    }
                    $table['where'] = substr($table['where'], 0, -3);
                }else{
                    $table['where'] .= $prm_where;
                }
            }

            if(is_numeric($prm_module_table)){
                $query = DB::table("ci_sis_master_data")
                            ->select(DB::raw("id as combo_key, name as combo_name"))
                            ->join('ci_sis_master_data_language', function($join){
                                $join->on("ci_sis_master_data.id", '=', "ci_sis_master_data_language.data__id");
                            })
                            ->where(array('module_table__id' => $prm_module_table, 'language__id' => 1))
                            ->orderBy("id")
                            ->get();
            }else{
                switch ($prm_module_table) {
                    case 'ci_sis_master_area':
                        if(empty($prm_where)){
                            $table['name'] = $prm_module_table." a join ci_sis_master_data b on b.id = a.area_type__id";
                            $table['fields']['name'] = "a.id combo_key,
                                                            case b.id
                                                            when 14 then name
                                                            when 15 then concat('-', name)
                                                            when 16 then concat('--', name)
                                                            when 17 then concat('---', name)
                                                            when 18 then concat('----', name)
                                                            end as combo_name";
                            $table['where'] = "WHERE b.module_table__id = 6";
                        }else{
                            $table['name'] = $prm_module_table;
                            $table['fields']['name'] = "id as combo_key, name as combo_name";
                        }
                    break;

                    case 'ci_sis_library_master_book':
                        $table['name'] = $prm_module_table;
                        $table['fields']['name'] = "id as combo_key, title as combo_name";
                    break;

                    case 'ci_system_language':
                        $table['name'] = $prm_module_table;
                        $table['fields']['name'] = "id as combo_key, name as combo_name";
                    break;

                    case 'ci_sis_organization_school':
                        $table['name'] = $prm_module_table;
                        $table['fields']['name'] = "id as combo_key, name as combo_name";
                    break;
                    
                    default:
                        
                    break;
                }

                $query = DB::select("select ".$table['fields']['name']." FROM ".$table['name']." ".$table['where']."");

                // $query = array_map(function ($value){
                //     return (array)$value;
                // }, $query);
            }

            $result = array();
            if($prm_return_array){
                foreach($query as $row){
                    $result[$row->combo_key] = $row->combo_name;
                }
            }else{
                $result = $query;
            }

            $return_value = [
                'status' => 'success',
                'code' => 200,
                'result' => $result
            ];
        }catch(QueryException $e){
            $return_value = [
                'status' => 'error',
                'code' => 500,
                'result' => null,
                'message' => $e->getMessage()
            ];
        }
        
        return $return_value;
    }

    public function generate($prm_type = '', $prm_table = ''){
        try{
            $result = '';
            switch($prm_type){
                case 'book_number':
                    $date = date('Y-m-d');
                    $get_book_count = DB::table($prm_table)
                                            ->where(DB::raw("to_char(created_date, 'YYYY-MM-DD') = '$date'"))
                                            ->count('number');
                    
                    $number_result = $get_book_count + 1;
                    $today_number = str_replace('-', '', $date);

                    if($get_book_count >= 99){
                        $result = 'B'.$today_number.$number_result;
                    }else if($get_book_count >= 9 && $get_book_count < 100){
                        $result = 'B'.$today_number.'0'.$number_result;
                    }else{
                        $result = 'B'.$today_number.'00'.$number_result;
                    }
                break;
            }

            $return_value = [
                'js_form_insert',
                'number' => $result,
                'code' => 200
            ];
        }catch(QueryException $e){
            $return_value = [
                'js_form_error',
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }

        return $return_value;
    }
}
