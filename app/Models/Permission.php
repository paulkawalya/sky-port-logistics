<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    
    public static function savePermission($model_component_id,$name,$display_name){

        $savePermission = new Permission();

        $savePermission->model_component_id = $model_component_id;

        $savePermission->name = $name;

        $savePermission->display_name = $display_name;

        try{

            return $savePermission->save();

        }catch(\Exception $message){}

    }


    public function modelcomponemt() : BelongsTo {

        return $this->belongsTo(ModelComponent::class,'model_component_id');
        
    }

}
