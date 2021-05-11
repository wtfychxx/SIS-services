<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;


class BookModel extends Model
{
    use HasFactory;

    protected $table = 'ci_sis_library_master_book';

    public function get(){
        try{
            $rawQuery = "id,
                            number,
                            title,
                            release_year,
                            author,
                            fn_sis_master_data_get_name(10, genre__id, 1) as genre";
            $data = DB::table($this->table)
                        ->select(DB::raw($rawQuery))
                        ->get();

            if($data->count() > 0){
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
        }catch(QueryException $e){
            $return_value = [
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage()
            ];
        }

        return $return_value;
    }

    public function put($prm_where = array()){
        try{
            $query = DB::table($this->table)
                            ->where($prm_where)
                            ->first();

            if(!is_null($query)){
                $return_value = [
                    'status' => 'success',
                    'result' => $query,
                    'code' => 200
                ];
            }else{
                $return_value = [
                    'status' => 'error',
                    'result' => null,
                    'message' => 'Cannot get data with '.$prm_where['id'],
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

    public function insert($data = array(), $prm_where = array()){
        try{
            if(empty($prm_where)){
                $query = DB::table($this->table)
                            ->insert($data);

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
                                    ->update($data);
    
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
                    'Oops..Error when save data to database!',
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
            $check = DB::table($this->table)->where($prm_where)->count();

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
                    'Oops..something went wrong',
                    'error',
                    'warning'
                ],
                'code' => 200
            ];
        }

        return $return_value;
    }
}
