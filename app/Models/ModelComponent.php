<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelComponent extends Model
{
    public static function saveModelComponent($name){

        $saveModelComponent = new ModelComponent();

        $saveModelComponent->name = $name;

        try{

            $saveModelComponent->save();

            return $saveModelComponent;

        }catch(\Exception $message){
            return ModelComponent::where('name',$name)->get()->last();
        }

    }

    public function permissions(){

        return $this->hasMany(Permission::class);
        
    }
}
