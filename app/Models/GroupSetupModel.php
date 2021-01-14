<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class GroupSetupModel extends Model
{
    use HasFactory;

    protected $table = 'ci_sis_user_group';

    public function put($prm_school = '', $prm_id = array()){
        try{
            if(!empty($prm_id)){
                $check = DB::table($this->table)->where($prm_id)->count();
                if($check > 0){
                    $query = DB::table($this->table)
                                    ->select(DB::raw("id, name, status, employee_default"))
                                    ->where($prm_id)
                                    ->first();

                    if(!is_null($query)){
                        $return_value = [
                            'status' => 'success',
                            'result' => $query,
                            'code' => 200
                        ];
                    }
                }else{
                    $return_value = [
                        'status' => 'error',
                        'result' => null,
                        'code' => 200,
                        'message' => 'Data with id '.$prm_where['id'].' not found!'
                    ];
                }
            }else{
                $query = DB::table($this->table)
                            ->select(DB::raw("id, name, status, employee_default"))
                            ->get();

                if($query->count() > 0){
                    $return_value = [
                        'status' => 'success',
                        'result' => $query,
                        'code' => 200
                    ];
                }else{
                    $return_value = [
                        'status' => 'error',
                        'result' => null,
                        'code' => 200,
                        'message' => 'Sorry there is no data yet.'
                    ];
                }
            }
        }catch(QueryException $e){
            $return_value = [
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage()
            ];
        }

        return $return_value;
    }

    public function insert($prm_data = array(), $prm_where = array()){
        try{
            if(empty($prm_where)){
                $query = DB::table($this->table)
                                ->insert($prm_data);

                if($query){
                    $return_value = [
                        'js_form_insert',
                        [
                            'Successfully Added New Data!',
                            'ok',
                            'success'
                        ],
                        'code' => 201
                    ];
                }
            }else{
                $check = DB::table($this->table)->where($prm_where)->count();

                if($check > 0){
                    $query = DB::table($this->table)
                                    ->where($prm_where)
                                    ->update($data);

                    if($query){
                        $return_value = [
                            'js_form_update',
                            [
                                'Successfully Update the Data!',
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
                            'Data with id '.$prm_where['id']. 'not found!',
                            'error',
                            'warning'
                        ],
                        'code' => 200
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
                    'Oops.. something wrong when save delete data!',
                    'error',
                    'warning'
                ],
                'code' => 200,
                'message' => $e->getMessage()
            ];
        }

        return $return_value;
    }
}
