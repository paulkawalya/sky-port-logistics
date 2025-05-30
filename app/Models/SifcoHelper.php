<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifcoHelper extends Model
{
    use HasFactory;


    
    public static function uploadImage($file)
    {      

        if (empty($file)) 

            return null;

        $destinationPath = public_path('documents');

        $ext = $file->getClientOriginalExtension();

        $file_url=time().\Str::random(12).'.'.$ext;

        $file->move($destinationPath,$file_url);
        
        return $file_url;

    }
}
