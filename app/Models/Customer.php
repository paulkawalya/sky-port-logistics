<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public static function saveCustomer($name,$phone_number) {

        $customers = Customer::where('phone_number',$phone_number)->get();

        if($customers->count() == 1) 
        
            $saveData = $customers->last();

        else

            $saveData = new Customer();

        $saveData->name = $name;

        $saveData->phone_number = $phone_number;

        $saveData->save();

        return $saveData;
        
    }
}
