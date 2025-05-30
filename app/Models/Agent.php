<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    public static function saveAgent($name,$phone_number) {

        $agents = Agent::where('name',$name)->get();

        if($agents->count() == 0)

            $saveAgent = new Agent();

        else

            $saveAgent = $agents->last();

        $saveAgent->name = $name;

        $saveAgent->phone_number = $phone_number;

        $saveAgent->save();

        return  $saveAgent;
        
    }
}
