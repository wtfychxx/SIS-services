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

    function __construct(){
        parent::__construct();

        $this->menu = array();
    }

    public function put($prm_school = array(), $prm_id = array()){
        try{
            if(!empty($prm_id)){
                $check = DB::table($this->table)->where($prm_id)->count();
                if($check > 0){
                    $query = DB::table($this->table)
                                    ->select(DB::raw("id, name, status, employee_default, auth_type__id"))
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
                            ->where($prm_school)
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

    public function insert($prm_data = array(), $prm_menus = array(), $prm_where = array()){
        try{
            if(empty($prm_where)){
                $query = DB::table($this->table)
                                ->insert($prm_data);

                $header['ID'] = DB::table($this->table)
                                ->select(DB::raw("currval('ci_sis_master_data_id_seq') as runid"))->value('runid');

                $message = "Successfully Added New Data!";
                $code = 201;
            }else{
                $check = DB::table($this->table)->where($prm_where)->count();

                if($check > 0){
                    $query = DB::table($this->table)
                                    ->where($prm_where)
                                    ->update($data);

                    $header['ID'] = $prm_where['id'];

                    $message = "Successfully Update the Data!";
                    $code = 202;
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

            if($query){
                $return_value = [
                    'js_form_insert',
                    [
                        $message,
                        'ok',
                        'success'
                    ],
                    'code' => $code
                ];
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

    public function setup(){
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

    public function getAccess($prm_id = ''){
        try{
            $rawQuery = "menus__id,
                            type,
                            concat(menus__id, ';', type) as value";
            $where = ['id' => $prm_id];
            $query = DB::table($this->table)
                        ->join('ci_sis_user_group_access', function($join){
                            $join->on('ci_sis_user_group.id', '=', 'ci_sis_user_group_access.group__id');
                        })
                        ->select(DB::raw($rawQuery))
                        ->where($where)
                        ->orderBy('menus__id')
                        ->get();

            $return_value = [
                'status' => 'success',
                'result' => $query,
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
                ]
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

    private function group_access_rights_set($prm_id = '', $prm_menus = array()){
        $where = ['group__id' => $prm_id];
        $check = DB::table('ci_sis_user_group_access')
                    ->where($where)
                    ->count();

        if($count > 0){
            DB::table('ci_sis_user_group_access')
                    ->where($where)
                    ->delete();

            if(is_array($prm_menus)){
                foreach($prm_menus as $val){
                    $access_rights = explode(';', $val);
                    $this->_menu_parent_get($prm_id, $access_rights[0], $access_rights[1]);
                }

                DB::table('ci_sis_user_group_access', $this->menu);
            }
        }
    }
    
    private function _menu_parent_get($prm_group_id, $prm_id, $prm_access_type){
        $query = DB::table('ci_system_menus')
                ->where(['id' => $prm_id]);

        if($query->count() > 0){
            $result = $query->get();

            if(!array_key_exists($key, $this->menu)){
                $this->menu[key] = [
                    'group__id' => $prm_group_id,
                    'menus__id' => $prm_id,
                    'type' => $prm_access_type
                ];
            }

            if($query->first()->parent_id != 0){
                $this->_menu_parent_get($prm_group_id, $query->first()->parent_id, 'view');
            }
        }
    }
}
