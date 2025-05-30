<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    protected $fillable = ['user_id', 'permission_id'];

    public static function checkUserPermision($user_id,$permission_id){

        $checkUserPermissions = UserPermission::where('user_id',$user_id)->where('permission_id',$permission_id)->get();

        if ( $checkUserPermissions->count() == 0)

            return false;

           else

            return  true;

    }

    public function permision() : BelongsTo {

        return $this->belongsTo(Permission::class,'permission_id');

    }

    public function user() : BelongsTo {

        return $this->belongsTo(User::class,'user_id');

    }
}
