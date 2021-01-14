<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_database extends Model
{
    use HasFactory;

    public function insert($prm_table = '', $prm_data){
        try{
            $query = DB::table($prm_table)->
                        insert($prm_data);
        }catch(Throwable $e){

        }
    }
}
