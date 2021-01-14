<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
// use App\Exceptions\CustomException;

class MasterDataModel extends Model
{
    use HasFactory;

    protected $table = 'ci_sis_master_data';
    protected $primaryKey;

    public function dataPut($prm_module_table){
        $rawQuery = "id,
                    name,
                    language__id,
                    (select name from ci_system_language b where b.id = language__id) as language,
                    description";
        $data = DB::table($this->table)
                    ->join('ci_sis_master_data_language', function($join){
                        $join->on($this->table.'.id', '=', 'ci_sis_master_data_language.data__id');
                    })
                    ->select(DB::raw($rawQuery))
                    ->where($prm_module_table)
                    ->orderBy('id')
                    ->get();
        
        if(count($data) > 0){
            $return_value = [
                'status' => 'success',
                'code' => 200,
                'result' => $data
            ];
        }else{
            $return_value = [
                'status' => 'error',
                'code' => 200,
                'result' => null,
                'message' => 'Sorry, there is no data yet.'
            ];
        }

        return $return_value;
    }

    public function getData($prm_module_table){
        $data = DB::table($this->table)
                ->join('ci_sis_master_data_language', function($join){
                    $join->on($this->table.'.id', '=', 'ci_sis_master_data_language.data__id');
                })
                ->select(DB::raw("id as combo_key, name as combo_name"))
                ->where($prm_module_table)
                // ->where("language__id", '1')
                ->orderBy("id")
                ->get();

        if($data->count() > 0){
            $return_value = $data;
        }else{
            $return_value = false;
        }

        return $return_value;
    }

    public function put($prm_data){
        $data = DB::table($this->table)
                    ->join('ci_sis_master_data_language', function($join){
                        $join->on($this->table.'.id', '=', 'ci_sis_master_data_language.data__id');
                    })
                    ->select(DB::raw("id, language__id, name, description"))
                    ->where($prm_data)
                    ->first();

        if(!is_null($data)){
            $return_value = $data;
        }else{
            $return_value = false;
        }

        return $return_value;
    }

    public function insert($prm_table = '', $prm_data = array(), $prm_where = ''){
        $tables = $this->table;
        if($prm_table != $this->table){
            $tables = $prm_table;
        }
        try{
            if($prm_where == ''){
                $data = DB::table($tables)
                    ->insert($prm_data);

                if($data){
                    $header['ID'] = '';
                    if($this->table == $tables){
                        $header['ID'] = DB::table($this->table)
                                            ->select(DB::raw("currval('ci_sis_master_data_id_seq') as runid"))->value('runid');
                    }
                    $return_value = [
                        'js_form_insert',
                        [
                            'Successfully added new data!',
                            'ok',
                            'success',
                        ],
                        'runid' => $header['ID'],
                        'code' => 201
                    ];
                }
            }else{
                $data = DB::table($tables)
                            ->where($prm_where)
                            ->update($prm_data);

                if($data){
                    $return_value = [
                        'js_form_update',
                        [
                            'Successfully update the data!',
                            'ok',
                            'success'
                        ],
                        'code' => 202
                    ];
                }
            }
        }catch(QueryException $e){
            $return_value = [
                'js_form_error',
                [
                    'Oops, i think something wrong when try save data to database',
                    'error',
                    'warning'
                ],
                'code' => 200,
                'message' => $e->getMessage()
            ];
        }

        return $return_value;
    }

    public function delete($prm_table = '', $prm_where = array()){
        try{
            $check_id = DB::table($prm_table)
                            ->where($prm_where)
                            ->count();

            if($check_id > 0){
                $query = DB::table($prm_table)->where($prm_where)->delete();
                if($query){
                    $check = DB::table($prm_table)->where('data__id', $prm_where['data__id'])->count();
                    if($check == 0){
                        DB::table($this->table)->where('id', $prm_where['data__id'])->delete();
                    }

                    $return_value = [
                        'js_form_delete',
                        [
                            'Successfully deleted the data!',
                            'ok',
                            'success'
                        ],
                        'code' => 200
                    ];
                }
            }else{
                $return_value = [
                    'js_form_error',
                    [
                        'Data with id '.$prm_where['data__id'].' not found!',
                        'error'
                    ],
                    'code' => 200
                ];
            }

        }catch(QueryException $e){
            $return_value = [
                'js_form_error',
                [
                    'Oops..something wrong when delete data!',
                    'error',
                    'warning'
                ],
                'status' => 200,
                'message' => $e->getMessage()
            ];
        }

        return $return_value;
    }

    public function checkAvailable($prm_where){
        $check_id = DB::table($this->table)
                        ->where('id', $prm_where['data__id'])
                        ->count();

        if($check_id == 0){
            $return_value = [
                'js_form_error',
                [
                    'Data with id '.$prm_where['data__id'].' not found!',
                    'error',
                    'warning'
                ],
                'code' => 200
            ];
        }else{
            $query = DB::table('ci_sis_master_data_language')
                        ->where($prm_where)
                        ->count();
    
            if($query > 0){
                $return_value = [
                    'js_form_check',
                    [
                        'Data with the same language already exists!',
                        'error',
                        'warning'
                    ],
                    'code' => 200
                ];
            }else{
                $return_value = [
                    'js_form_check',
                    [
                        'Available to add',
                        'ok',
                        'success'
                    ],
                    'code' => 200
                ];
            }
        }

        return $return_value;
    }
}
