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

    protected $fillable = [
        'transit_id',
        'user_id',
        'customer_id',
        'agent_id',
        'shipper',
        'mark',
        'bl_packages',
        'number_of_packages',
        'unit_of_measure',
        'weight',
        'volume',
        'type',
        'description',
        'cbm',
        'cargo_status',
        'warehouse_location',
        'received_in_store_at',
        'dispatched_at',
        'receiver_name',
        'receiver_phone',
        'receiver_id_number',
        'receiver_vehicle_no',
        'gate_pass_number',
        'checked_out_by',
        'checkout_notes',
    ];

    protected $casts = [
        'received_in_store_at' => 'datetime',
        'dispatched_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Shippment $shipment) {
            if (empty($shipment->user_id)) {
                $shipment->user_id = Auth::id() ?? 1;
            }
            if (empty($shipment->agent_id)) {
                $firstAgent = Agent::first();
                if (!$firstAgent) {
                    $firstAgent = Agent::create(['name' => 'Sifcargo Direct', 'phone_number' => '+256700000000']);
                }
                $shipment->agent_id = $firstAgent->id;
            }
        });
    }

    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    public function getDaysInStoreAttribute(): ?int
    {
        if (!$this->received_in_store_at) {
            return null;
        }
        $end = $this->dispatched_at ?? now();
        return (int) $this->received_in_store_at->diffInDays($end);
    }

    public static function generateGatePassNumber(): string
    {
        $year = date('Y');
        $count = static::whereNotNull('gate_pass_number')->count() + 1;
        return 'GP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

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
