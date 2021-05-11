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
                        'message' => 'Data with id '.$prm_id['id'].' not found!'
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

    public function setup($prm_school_id = '', $prm_id = ''){
        try{
            $rawQuery = "ci_system_menus.id,
                            parent_id,
                            ci_system_menus_language.name,
                            ci_system_menus_detail.type,
                            concat(ci_system_menus.id, ';', ci_system_menus_detail.type) as value";

            $query = DB::table('ci_system_menus')
                        ->join('ci_system_menus_language', function($join){
                            $join->on('ci_system_menus.id', '=', 'ci_system_menus_language.menus__id');
                        })
                        ->join('ci_system_menus_detail', function($join){
                            $join->on('ci_system_menus.id', '=', 'ci_system_menus_detail.menus__id');
                        })
                        ->select(DB::raw($rawQuery))
                        ->where(array('language__id' => 1))
                        ->orderBy('ci_system_menus.id')
                        ->orderBy('ci_system_menus_detail.id');
                        
            $result = $query->get();
            $lastid = $query->first()->id;
            
            $data = $this->mapping($lastid ,$result);

            $return_value = [
                'status' => 'success',
                'result' => $data,
                'code' => 200
            ];
        }catch(QueryException $e){
            $return_value = [
                'js_form_error',
                [
                    'Error when save data to database!',
                    'error',
                    'warning',
                    $e->getMessage()
                ],
                'code' => 200
            ];
        }

        return $return_value;
    }

    private function mapping($prm_last_id = '', $prm_data = array()){
        $last_menusid = $prm_last_id;
        $counter = 0;
        $count = 0;
        $detail_count = 0;

        foreach($prm_data as $row){
            // print_r($row);
            if($count === 0){
                $data[] = [
                    'id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'name' => $row->name,
                    'child' => false
                ];
                $count++;
            }

            if($last_menusid === $row->id){
                $data[$counter]['item'][] = [
                    'name' => $row->type,
                    'value' => $row->value
                ];
            }else{
                $last_menusid = $row->id;
                $counter++;

                $data[] = [
                    'id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'name' => $row->name,
                    'child' => false
                ];
                $data[$counter]['item'][] = [
                    'name' => $row->type,
                    'value' => $row->value
                ];
            }

        }

        $last_menusid = $prm_last_id;
        $counter = 0;

        foreach($prm_data as $row){
            foreach($prm_data as $sub){
                if($row->id === $sub->parent_id){
                    $data[$counter]['child'] = true;
                }

                if($last_menusid !== $row->id){
                    $last_menusid = $row->id;
                    $counter++;
                }
            }
        }

        return $data;
    }
}
