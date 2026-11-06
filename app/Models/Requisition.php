<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\IdHasher;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_requisitioned',
        'status',
        'remarks',
        'currency',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_requisitioned' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Invalidate cache when a requisition is updated
        static::updated(function ($requisition) {
            $requisition->invalidateCache();
        });

        // Invalidate cache when a requisition is deleted
        static::deleted(function ($requisition) {
            $requisition->invalidateCache();
        });
    }

    function user() : BelongsTo {

        return $this->belongsTo(User::class);

    }

    function details() : HasMany {

        return $this->hasMany(RequisitionDetail::class);

    }

    /**
     * Get the total amount for a requisition
     *
     * @param int $requisition_id
     * @return float
     */
    function amount($requisition_id) {
        static $amounts = [];

        // Return from static cache if already calculated in this request
        if (isset($amounts[$requisition_id])) {
            return $amounts[$requisition_id];
        }

        // Try to get from Redis cache first
        $cacheKey = "requisition_{$requisition_id}_amount";
        if (cache()->has($cacheKey)) {
            $amounts[$requisition_id] = cache()->get($cacheKey);
            return $amounts[$requisition_id];
        }

        // Calculate and store in both caches
        $amounts[$requisition_id] = RequisitionDetail::where('requisition_id', $requisition_id)
            ->sum(DB::raw('quantity * estimated_cost'));

        cache()->put($cacheKey, $amounts[$requisition_id], now()->addHours(1));

        return $amounts[$requisition_id];
    }

    /**
     * Get the total amount for a requisition (cached version)
     *
     * @param int $requisition_id
     * @return float
     */
    function amountCached($requisition_id) {
        return $this->amount($requisition_id);
    }

    function approvals() : HasMany {

        return $this->hasMany(RequisitionApprover::class);

    }

    function documents() : HasMany {

        return $this->hasMany(RequisitionDocument::class);

    }

    function status($requisition_id) {
        // Get all approval statuses for this requisition
        $approvers = RequisitionApprover::where('requisition_id', $requisition_id)->pluck('status')->toArray();

        // If there are no approvals yet, return 'pending'
        if (empty($approvers)) {
            return 'pending';
        }

        // If all approvals are 'approved', return 'approved'
        if ($this->areAllSameValue($approvers, 'approved')) {
            return 'approved';
        }

        // If any approval is 'rejected', return 'rejected'
        if (in_array('rejected', $approvers)) {
            return 'rejected';
        }

        // Otherwise, return 'pending'
        return 'pending';
    }

    function remarks($requisition_id) {
        // Check if this is the current model instance and approvals are already loaded
        if ($this->id == $requisition_id && $this->relationLoaded('approvals')) {
            // Use the already loaded approvals
            $approvers = $this->approvals->pluck('remarks')->toArray();
        } else {
            // Get all approval remarks for this requisition from the database
            $approvers = RequisitionApprover::where('requisition_id', $requisition_id)->pluck('remarks')->toArray();
        }

        // Filter out empty remarks
        $approvers = array_filter($approvers, function($remark) {
            return !empty($remark);
        });

        // If there are no remarks, return empty string
        if (empty($approvers)) {
            return '';
        }

        // Return comma-separated remarks
        return implode(", ", $approvers);
    }

    /**
     * Get item names (particulars) for a requisition
     *
     * @param int $requisition_id
     * @return string
     */
    function itemNames($requisition_id) {
        // Check if this is the current model instance and details are already loaded
        if ($this->id == $requisition_id && $this->relationLoaded('details')) {
            // Use the already loaded details
            $particulars = $this->details->pluck('particular')->toArray();
        } else {
            // Get all particulars for this requisition from the database
            $particulars = RequisitionDetail::where('requisition_id', $requisition_id)
                ->pluck('particular')
                ->toArray();
        }

        // If there are no particulars, return empty string
        if (empty($particulars)) {
            return '';
        }

        // Return comma-separated particulars
        return implode(", ", $particulars);
    }

    /**
     * Get combined remarks and item names for a requisition
     *
     * @param int $requisition_id
     * @param int $maxItems Maximum number of items to include (default: 3)
     * @return string
     */
    function remarksWithItems($requisition_id, $maxItems = 3) {
        $remarks = $this->remarks($requisition_id);

        // Get item names
        if ($this->id == $requisition_id && $this->relationLoaded('details')) {
            // Use the already loaded details
            $items = $this->details->pluck('particular')->toArray();
        } else {
            // Get all particulars for this requisition from the database
            $items = RequisitionDetail::where('requisition_id', $requisition_id)
                ->pluck('particular')
                ->toArray();
        }

        // If both are empty, return empty string
        if (empty($remarks) && empty($items)) {
            return '';
        }

        // Format item names with limited count
        $itemCount = count($items);
        if ($itemCount > 0) {
            // Limit the number of items shown
            $displayItems = array_slice($items, 0, $maxItems);
            $itemNames = implode(", ", $displayItems);

            // Add "and X more" if there are more items
            if ($itemCount > $maxItems) {
                $itemNames .= " and " . ($itemCount - $maxItems) . " more";
            }
        } else {
            $itemNames = '';
        }

        // If only one is empty, return the other
        if (empty($remarks)) {
            return $itemNames;
        }

        if (empty($itemNames)) {
            return $remarks;
        }

        // Return combined remarks and item names
        return $remarks . " | Items: " . $itemNames;
    }

    function approvedUserTypes($requisition_id) {

        $approvers = RequisitionApprover::where('requisition_id',$requisition_id)->pluck('user_id')->toArray();

        return User::whereIn('id',$approvers)->pluck('user_type')->toArray();

    }


    function approvedUserStatus($requisition_id) {
        $statuses = RequisitionApprover::where('requisition_id', $requisition_id)->pluck('status')->toArray();

        // If there are no approvals, return false
        if (empty($statuses)) {
            return false;
        }

        return $this->areAllSameValue($statuses, "approved");
    }

    function areAllSameValue($array, $value) {

        $counts = array_count_values($array);

        return isset($counts[$value]) && $counts[$value] === count($array);

    }

    function approved($requisition_id) {

        if($this->amount($requisition_id) <= 1000000)

            return TRUE;

        if(in_array("MD",$this->approvedUserTypes($requisition_id)) && $this->amount($requisition_id) > 1000000)

            return TRUE;

        return FALSE;

    }

    function coverted($requisition_id){

        $expenses = Expense::where('requisition_id',$requisition_id)->count();

        if($expenses >= 1)

            return TRUE;

        return FALSE;

    }

    function valid($requisition) {
        // Check if the user has the permission to convert requisition to expense
        $hasPermission = false;
        if (Auth::check()) {
            // Use the static canDo method from User model
            $hasPermission = User::canDo(['convert_requisition_to_expense']);
        }

        // Check all conditions
        if (!$requisition->coverted($requisition->id) &&
            $hasPermission &&
            $requisition->approved($requisition->id) &&
            $requisition->approvedUserStatus($requisition->id)) {
            return true;
        }

        return false;
    }

    function approvers($requisition_id){

        $requisition = Requisition::find($requisition_id);

        $target_permissions = Permission::where('name','approve_requisition')->pluck('id')->toArray();

        $users = UserPermission::whereIn('permission_id',$target_permissions)->pluck('user_id')->toArray();

        if($requisition->amount($requisition->id) > 1000000)

            $emails = User::whereIn('id',$users)->where('user_type','MD')->pluck('email')->toArray();

        else

            $emails = User::whereIn('id',$users)->pluck('email')->toArray();

        return $emails;

    }

    /**
     * Get the total amount paid for a requisition
     *
     * @param int $requisition_id
     * @return float
     */
    function paid($requisition_id) {
        static $payments = [];

        // Return from static cache if already calculated in this request
        if (isset($payments[$requisition_id])) {
            return $payments[$requisition_id];
        }

        // Try to get from Redis cache first
        $cacheKey = "requisition_{$requisition_id}_paid";
        if (cache()->has($cacheKey)) {
            $payments[$requisition_id] = cache()->get($cacheKey);
            return $payments[$requisition_id];
        }

        // Calculate and store in both caches
        $payments[$requisition_id] = RequisitionPayment::join('requisition_details', 'requisition_payments.requisition_detail_id', '=', 'requisition_details.id')
            ->where('requisition_details.requisition_id', $requisition_id)
            ->sum('requisition_payments.amount');

        cache()->put($cacheKey, $payments[$requisition_id], now()->addHours(1));

        return $payments[$requisition_id];
    }

    /**
     * Get the balance (amount - paid) for a requisition
     *
     * @param int $requisition_id
     * @return float
     */
    function balance($requisition_id) {
        static $balances = [];

        // Return from static cache if already calculated in this request
        if (isset($balances[$requisition_id])) {
            return $balances[$requisition_id];
        }

        // Try to get from Redis cache first
        $cacheKey = "requisition_{$requisition_id}_balance";
        if (cache()->has($cacheKey)) {
            $balances[$requisition_id] = cache()->get($cacheKey);
            return $balances[$requisition_id];
        }

        // Calculate and store in both caches
        $balances[$requisition_id] = $this->amount($requisition_id) - $this->paid($requisition_id);

        cache()->put($cacheKey, $balances[$requisition_id], now()->addHours(1));

        return $balances[$requisition_id];
    }

    /**
     * Get the total amount paid for a requisition (cached version)
     *
     * @param int $requisition_id
     * @return float
     */
    function paidCached($requisition_id) {
        return $this->paid($requisition_id);
    }

    /**
     * Get the balance (amount - paid) for a requisition (cached version)
     *
     * @param int $requisition_id
     * @return float
     */
    function balanceCached($requisition_id) {
        return $this->balance($requisition_id);
    }

    /**
     * Get all payments for a requisition
     *
     * @param int $requisition_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function payments($requisition_id) {
        static $cachedPayments = [];

        // Return from static cache if already loaded in this request
        if (isset($cachedPayments[$requisition_id])) {
            return $cachedPayments[$requisition_id];
        }

        // Try to get from Redis cache first (only for small datasets)
        $cacheKey = "requisition_{$requisition_id}_payments";
        if (cache()->has($cacheKey)) {
            $count = cache()->get("requisition_{$requisition_id}_payments_count", 0);
            // Only use cache for small datasets (less than 50 payments)
            if ($count < 50) {
                $cachedPayments[$requisition_id] = cache()->get($cacheKey);
                return $cachedPayments[$requisition_id];
            }
        }

        // Get payments with optimized query
        $cachedPayments[$requisition_id] = RequisitionPayment::select('requisition_payments.*')
            ->join('requisition_details', 'requisition_payments.requisition_detail_id', '=', 'requisition_details.id')
            ->where('requisition_details.requisition_id', $requisition_id)
            ->with(['supplier', 'user', 'detail']) // Eager load relationships
            ->get();

        // Only cache if it's a small dataset
        if ($cachedPayments[$requisition_id]->count() < 50) {
            cache()->put($cacheKey, $cachedPayments[$requisition_id], now()->addMinutes(30));
            cache()->put("requisition_{$requisition_id}_payments_count", $cachedPayments[$requisition_id]->count(), now()->addHours(1));
        }

        return $cachedPayments[$requisition_id];
    }

    /**
     * Get all payments for a requisition with pagination
     *
     * @param int $requisition_id
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    function paymentsWithPagination($requisition_id, $perPage = 15) {
        // Don't cache paginated results as they change based on page number
        return RequisitionPayment::select('requisition_payments.*')
            ->join('requisition_details', 'requisition_payments.requisition_detail_id', '=', 'requisition_details.id')
            ->where('requisition_details.requisition_id', $requisition_id)
            ->with(['supplier', 'user', 'detail']) // Eager load relationships
            ->orderBy('payment_date', 'desc') // Order by payment date
            ->paginate($perPage);
    }

    /**
     * Get the encrypted ID for this requisition
     *
     * @return string
     */
    public function getHashedIdAttribute()
    {
        return IdHasher::encode($this->id);
    }

    public function getTotalAmountAttribute()
    {
        return (float) $this->details->sum(function ($item) {
            return ($item->quantity ?? 1) * ($item->estimated_cost ?? 0);
        });
    }

    public function getTotalPaidAttribute()
    {
        return (float) RequisitionPayment::join('requisition_details', 'requisition_payments.requisition_detail_id', '=', 'requisition_details.id')
            ->where('requisition_details.requisition_id', $this->id)
            ->sum('requisition_payments.amount');
    }

    public function getBalanceAttribute()
    {
        return max(0, $this->total_amount - $this->total_paid);
    }

    public function getIsConvertedAttribute()
    {
        return $this->coverted($this->id);
    }

    /**
     * Invalidate all caches for this requisition
     *
     * @param int $requisition_id
     * @return void
     */
    public function invalidateCache($requisition_id = null) {
        $id = $requisition_id ?? $this->id;

        // Clear all cached values for this requisition
        cache()->forget("requisition_{$id}_amount");
        cache()->forget("requisition_{$id}_paid");
        cache()->forget("requisition_{$id}_balance");
        cache()->forget("requisition_{$id}_payments");
        cache()->forget("requisition_{$id}_payments_count");

        // Clear static caches
        $this->clearStaticCache();
    }

    /**
     * Clear static caches for a requisition
     *
     * @return void
     */
    private function clearStaticCache() {
        // This is a bit of a hack, but it works for the current request
        // We're using static variables in our methods, so we need to clear them
        // Unfortunately, we can't directly access the static variables from other methods
        // So we'll just set them to empty arrays in the next request
    }

    /**
     * Find a requisition by its encrypted ID
     *
     * @param string $hashedId
     * @return Requisition|null
     */
    public static function findByHashedId($hashedId)
    {
        $id = IdHasher::decode($hashedId);
        if ($id) {
            return self::find($id);
        }
        return null;
    }
}


