<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class MasterAreaModel extends Model
{
    use HasFactory;

    protected $table = 'ci_sis_master_area';
    protected $primaryKey = 'id';

    public function getAllData(){
        DB::enableQueryLog();
        $rawQuery = "id,
                        name,
                        (select name from ci_sis_master_data_language where language__id = 1 and data__id = area_type__id) as area_type,
                        coalesce(cast(nullif(postcode, 0) as text), 'N/A') as postcode";
        $data = DB::table($this->table)
                        ->select(DB::raw($rawQuery))
                        ->orderBy("id")
                        ->get();

        if($data->count() > 0){
            $return_value = [
                'status' => 'success',
                'result' => $data,
                'code' => 200
            ];
        }else{
            $return_value = [
                'status' => 'error',
                'result' => null,
                'message' => 'Sorry, there is no data yet.',
                'code' => 200
            ];
        }

        return $return_value;
    }

    public function getModalData($prm_id = ''){
        try{
            $data = DB::table($this->table)
                        ->where('id', $prm_id)
                        ->first();


            if(!is_null($data)){
                $return_value  = [
                    'status' => 'success',
                    'result' => $data,
                    'code' => 200
                ];
            }else{
                $return_value = [
                    'status' => 'error',
                    'result' => null,
                    'message' => 'Cannot get data with id '.$prm_id,
                    'code' => 200
                ];
            }
        }catch(QueryException $e){
            $return_value = [
                'status' => 'error',
                'result' => null,
                'message' => $e->getMessage(),
                'code' => 500
            ];
        }

        return $return_value;
    }

    public function insert($data, $prm_where = array()){
        try{
            if(empty($prm_where)){
                $query = DB::table($this->table)
                                ->insert($data);
    
                if($query){
                    $return_value = [
                        'js_form_insert',
                        [
                            'Successfully added new data!',
                            'ok',
                            'success'
                        ],
                        'code' => 201
                    ];
                }
            }else{
                $check = DB::table($this->table)
                            ->where($prm_where)
                            ->count();
                
                if($check > 0){
                    $query = DB::table($this->table)
                                    ->where($prm_where)
                                    ->update($data);

                    if($query){
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
                }else{
                    $return_value = [
                        'js_form_error',
                        [
                            'Data with id '.$prm_where['id'].' not found!',
                            'error',
                            'warning'
                        ],
                        'code' => 500
                    ];
                }
            }

        }catch(QueryException $e){
            $return_value = [
                'js_form_error',
                [
                    'Error when save data to database!',
                    'error',
                    'warning'
                ],
                'message' => $e->getMessage(),
                'code' => 200
            ];
        }

        return $return_value;
    }

    public function delete($prm_where = array()){
        try{
            $check = DB::table($this->table)
                            ->where($prm_where)
                            ->count();

            if($check > 0){
                $query = DB::table($this->table)
                                ->where($prm_where)
                                ->delete();

                if($query){
                    $return_value = [
                        'js_form_delete',
                        [
                            'Successfully delete the data!',
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
                        'Data with id '.$prm_where['id'].' not found!',
                        'error',
                        'warning'
                    ],
                    'code' => 200
                ];
            }
        }catch(QueryException $e){
            $return_value = [
                'js_form_error',
                [
                    'Error when delete data!',
                    'error',
                    'warning'
                ],
                'code' => 200
            ];
        }

        return $return_value;
    }
}
