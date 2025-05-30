<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Shippment extends Model
{
    use HasFactory;

    function user() : BelongsTo {

        return $this->belongsTo(User::class);
        
    }

    function documents() : HasMany {

        return $this->hasMany(ShippmentDocument::class);
        
    }

    function transit() : BelongsTo {

        return $this->belongsTo(Transit::class);
        
    }

    function customer() : BelongsTo {

        return $this->belongsTo(Customer::class);
        
    }

    function agent() : BelongsTo {

        return $this->belongsTo(Agent::class);
        
    }

    function invoices() : HasMany {

        return $this->hasMany(Invoice::class);
        
    }

    function tranfers() : HasMany {

        return $this->hasMany(ShippmentTranfer::class);
        
    }

    public static function saveShipment($transit_id,$customer_id,$agent_id,$shipper,$mark,$bl_packages,$number_of_packages,$unit_of_measure,$weight,$volume,$type,$description,$updated_id=null) {


        if(!empty($updated_id))

            $saveShipment = Shippment::find($updated_id);

        else

            $saveShipment = new Shippment();

        $saveShipment->transit_id = $transit_id;

        $saveShipment->user_id = Auth::id();

        $saveShipment->customer_id = $customer_id;

        $saveShipment->agent_id = $agent_id;

        $saveShipment->shipper = $shipper;

        $saveShipment->mark = $mark;

        $saveShipment->bl_packages =  $bl_packages;

        $saveShipment->number_of_packages = $number_of_packages;

        $saveShipment->unit_of_measure = $unit_of_measure;

        $saveShipment->weight = $weight;

        $saveShipment->volume = $volume;

        $saveShipment->type = $type;

        $saveShipment->description = $description; 
            
        $saveShipment->cbm = ($volume*1.5);

        $saveShipment->save();

        return $saveShipment;
 
        
    }
}
