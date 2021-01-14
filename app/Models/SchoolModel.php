<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class SchoolModel extends Model
{
    use HasFactory;

    protected $table = 'ci_sis_organization_school';

    public function dataPut($prm_where = array()){
        try{
            if(empty($prm_where)){
                $query = DB::table($this->table)
                            ->get();

                if($query->count() > 0){
                    $return_value = [
                        'status' => 'success',
                        'code' => 200,
                        'result' => $query
                    ];
                }else{
                    $return_value = [
                        'status' => 'error',
                        'code' => 200,
                        'result' => null,
                        'message' => 'Sorry, there is no data yet.'
                    ];
                }
            }else{
                $query = DB::table($this->table)
                                ->where($prm_where)
                                ->first();

                if($query){
                    $return_value = [
                        'status' => 'success',
                        'code' => 200,
                        'result' => $query
                    ];
                }else{
                    $return_value = [
                        'status' => 'error',
                        'code' => 200,
                        'result' => null,
                        'message' => 'Cannot get data with id '.$prm_where['id']
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
                $check = DB::table($this->table)
                            ->where($prm_where)
                            ->count();

                if($check == 0){
                    $return_value = [
                        'js_form_error',
                        [
                            'Data with id '.$prm_where['id'].' not found!',
                            'error',
                            'warning'
                        ],
                        'code' => 200
                    ];
                }else{
                    $query = DB::table($this->table)
                                    ->where($prm_where)
                                    ->update($prm_data);
    
                    if($query){
                        $return_value = [
                            'js_form_update',
                            [
                                'Successfully Update the data!',
                                'ok',
                                'success'
                            ],
                            'code' => 202
                        ];
                    }
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
                'code' => 200,
                'message' => $e->getMessage()
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
                    'Oops..Something went wrong when deleted the data!',
                    'error',
                    'warning'
                ],
                'code' => 200
            ];
        }

        return $return_value;
    }
}
