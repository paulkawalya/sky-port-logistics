<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transit extends Model
{
    use HasFactory;

    public  function user() : BelongsTo {

        return $this->belongsTo(User::class);

    }


    public function statuses() : HasMany {
        return $this->hasMany(TransitStatus::class);
    }
    
    /**
     * Get the latest status for this transit
     * 
     * @return string
     */
    public function status() {
        $status = $this->statuses()->latest('created_at')->first();
        return $status ? $status->status : 'N/A';
    }



    public  function documents() : HasMany {

        return $this->hasMany(TransitDocument::class);

    }

    public function shippments() : HasMany {
        return $this->hasMany(Shippment::class);
    }
    
    /**
     * Get the first shipment associated with this transit
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shippment() {
        return $this->hasOne(Shippment::class)->latest();
    }


    public static function transitStatus($status){

        $transits = Transit::get();

        $counter = 0;

        foreach ($transits as $transit) {

            //what is the last status of this truck?

            $transit_status = TransitStatus::where('transit_id',$transit->id)->get();

            if($transit_status->count() == 0) continue;

            $transtStatus = $transit_status->last();

            if($transtStatus->status == $status)

                $counter++;
        }

        return $counter;

    }


    public static function pendingTransit(){

        $transits = Transit::orderBy('created_at', 'desc')->get();

        $containers = [];

        foreach ($transits as $transit) {

            //what is the last status of this truck?

            $transit_status = TransitStatus::where('transit_id',$transit->id)->get();

            if($transit_status->count() == 0) continue;

            $transtStatus = $transit_status->last();

            // if($transtStatus->status != "In Warehouse")

            $containers[] = $transtStatus->transit_id;
        }

        return Transit::whereIn('id',$containers)->orderBy('created_at', 'desc')->get();

    }

    /**
     * Get all containers/transits regardless of status
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllTransits()
    {
        return Transit::orderBy('created_at', 'desc')->get();
    }

    // Old status method has been replaced by getStatusById

    /**
     * Get the status of a transit by ID (static method)
     * 
     * @param int $transit_id
     * @return string
     */
    public static function getStatusById($transit_id)
    {
        // Use the latest() method to get the most recent status directly from the database
        $transit_status = TransitStatus::where('transit_id', $transit_id)
                                    ->latest('created_at')
                                    ->first();

        // If no status is found, return "N/A"
        if (!$transit_status) {
            return "N/A";
        }

        // Return the latest status
        return $transit_status->status;
    }
    



}
