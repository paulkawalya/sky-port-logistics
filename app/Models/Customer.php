<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
    ];

    public function shippments(): HasMany
    {
        return $this->hasMany(Shippment::class, 'customer_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Invoice::class, Shippment::class, 'customer_id', 'shippment_id');
    }

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
