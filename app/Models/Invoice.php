<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use App\Helpers\IdHasher;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Double;

class Invoice extends Model
{
    use HasFactory;

    /**
     * Cache duration in minutes (1 hour)
     */
    const CACHE_DURATION = 60;

    /**
     * The relationships that should be eager loaded.
     *
     * @var array
     */
    protected $with = []; // Remove eager loading to optimize queries
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shippment_id',
        'user_id',
        'date_invoiced',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_invoiced' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total_amount',
        'paid_amount',
        'balance',
        'amount',
        'payment_status',
    ];


    /**
     * Get the invoice details.
     */
    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    /**
     * Get the invoice payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /**
     * Get the user that created the invoice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipment associated with the invoice.
     */
    public function shippment(): BelongsTo
    {
        return $this->belongsTo(Shippment::class, 'shippment_id');
    }

    /**
     * Get the customer for the invoice through the shippment relationship
     */
    public function customer()
    {
        return $this->shippment->customer();
    }

    function taxinvoices() : HasMany {

        return $this->hasMany(InvoiceTax::class);

    }


    /**
     * Get the base amount (without tax)
     */
    public function getAmountAttribute() {
        $cacheKey = 'invoice_amount_' . $this->id;
        
        return cache()->remember($cacheKey, self::CACHE_DURATION, function() {
            return $this->details()->sum(DB::raw('quantity * unit_price'));
        });
    }

    /**
     * Get the total amount including tax with optimized query
     */
    public function getTotalAmountAttribute(): float
    {
        $cacheKey = 'invoice_total_amount_' . $this->id;
        
        return (float) cache()->remember($cacheKey, self::CACHE_DURATION, function() {
            // Get base amount and tax rate in a single query
            $result = $this->details()
                ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as subtotal')
                ->addSelect([
                    'total_tax' => $this->taxinvoices()
                        ->select(DB::raw('COALESCE(SUM((percentage_tax / 100) * (SELECT SUM(quantity * unit_price) FROM invoice_details WHERE invoice_id = invoice_taxes.invoice_id)), 0)'))
                ])
                ->first();
            
            return round(($result->subtotal ?? 0) + ($result->total_tax ?? 0), 2);
        });
    }

    /**
     * Get the total paid amount with optimized query
     */
    public function getPaidAmountAttribute(): float
    {
        $cacheKey = 'invoice_paid_amount_' . $this->id;
        
        return (float) cache()->remember($cacheKey, self::CACHE_DURATION, function() {
            return $this->payments()
                ->selectRaw('COALESCE(SUM(amount_paid), 0) as total_paid')
                ->value('total_paid') ?: 0;
        });
    }

    /**
     * Get the remaining balance
     */
    public function getBalanceAttribute(): float
    {
        return round($this->total_amount - $this->paid_amount, 2);
    }

    /**
     * Get the payment status
     * 
     * @return string One of: 'unpaid', 'partially_paid', 'paid'
     */
    public function getPaymentStatusAttribute(): string
    {
        $paid = $this->paid_amount;
        $total = $this->total_amount;
        
        if ($paid <= 0) {
            return 'unpaid';
        }
        
        if (abs($total - $paid) < 0.01) { // Handle floating point comparison
            return 'paid';
        }
        
        return 'partially_paid';
    }
    
    /**
     * Check if invoice is overdue (unpaid and older than 30 days)
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->balance > 0 && 
               $this->created_at->lt(now()->subDays(30)) &&
               $this->paid_amount < $this->total_amount;
    }
    
    /**
     * Clear all cached values for this invoice
     */
    public function clearCache(): void
    {
        $cacheKeys = [
            'invoice_amount_' . $this->id,
            'invoice_total_amount_' . $this->id,
            'invoice_paid_amount_' . $this->id,
        ];
        
        foreach ($cacheKeys as $key) {
            cache()->forget($key);
        }
    }

    // Legacy methods for backward compatibility
    /**
     * Legacy method for backward compatibility
     * 
     * @param int|null $invoice_id Unused parameter, kept for backward compatibility
     * @return float
     */
    public function amount($invoice_id = null): float
    {
        return (float) $this->amount;
    }

    /**
     * Legacy method for backward compatibility
     * 
     * @param int|null $invoice_id Unused parameter, kept for backward compatibility
     * @return float
     */
    public function totalAmount($invoice_id = null): float
    {
        return (float) $this->total_amount;
    }

    /**
     * Legacy method for backward compatibility
     * 
     * @param int|null $invoice_id Unused parameter, kept for backward compatibility
     * @return float
     */
    public function paidAmount($invoice_id = null): float
    {
        return (float) $this->paid_amount;
    }

    /**
     * Legacy method for backward compatibility
     * 
     * @param int|null $invoice_id Unused parameter, kept for backward compatibility
     * @return float
     */
    public function balance($invoice_id = null): float
    {
        return (float) $this->balance;
    }


    /**
     * Save a new invoice for the given shipment
     * 
     * @param int $shippment_id
     * @return self
     */
    public static function saveInvoice($shippment_id)
    {
        $invoice = new self([
            'shippment_id' => $shippment_id,
            'user_id' => Auth::id() ?? 1,
            'date_invoiced' => now(),
        ]);

        $invoice->save();
        return $invoice;
    }
    
    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // Clear cache when an invoice is saved or deleted
        static::saved(function ($invoice) {
            $invoice->clearCache();
            cache()->forget('paid_invoice_ids');
            cache()->forget('partially_paid_invoice_ids');
            cache()->forget('pending_invoice_ids');
            cache()->forget('overdue_invoice_ids');
        });
        
        static::deleted(function ($invoice) {
            $invoice->clearCache();
            cache()->forget('paid_invoice_ids');
            cache()->forget('partially_paid_invoice_ids');
            cache()->forget('pending_invoice_ids');
            cache()->forget('overdue_invoice_ids');
        });
    }


    /**
     * Get IDs of paid invoices
     */
    public static function paidInvoices(): array
    {
        return cache()->remember('paid_invoice_ids', self::CACHE_DURATION, function() {
            // Get all invoices where the sum of payments equals or exceeds the total amount
            return self::select('invoices.id')
                ->leftJoin('invoice_payments', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('invoice_taxes', 'invoices.id', '=', 'invoice_taxes.invoice_id')
                ->groupBy('invoices.id')
                ->havingRaw('COALESCE(SUM(invoice_payments.amount_paid), 0) >= (
                    COALESCE(SUM(invoice_details.quantity * invoice_details.unit_price), 0) * 
                    (1 + COALESCE(SUM(invoice_taxes.percentage_tax / 100), 0))
                )')
                ->pluck('invoices.id')
                ->toArray();
        });
    }

    /**
     * Get IDs of partially paid invoices
     */
    public static function partiallyPaidInvoices(): array
    {
        return cache()->remember('partially_paid_invoice_ids', self::CACHE_DURATION, function() {
            $paidIds = self::paidInvoices();
            
            // Get invoices with some payment but not fully paid
            return self::select('invoices.id')
                ->leftJoin('invoice_payments', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('invoice_taxes', 'invoices.id', '=', 'invoice_taxes.invoice_id')
                ->whereNotIn('invoices.id', $paidIds)
                ->groupBy('invoices.id')
                ->havingRaw('COALESCE(SUM(invoice_payments.amount_paid), 0) > 0')
                ->havingRaw('COALESCE(SUM(invoice_payments.amount_paid), 0) < (
                    COALESCE(SUM(invoice_details.quantity * invoice_details.unit_price), 0) * 
                    (1 + COALESCE(SUM(invoice_taxes.percentage_tax / 100), 0))
                )')
                ->pluck('invoices.id')
                ->toArray();
        });
    }
    
    /**
     * Get IDs of pending invoices (not paid, not partially paid, and not overdue)
     */
    public static function pendingInvoices(): array
    {
        return cache()->remember('pending_invoice_ids', self::CACHE_DURATION, function() {
            $paidIds = self::paidInvoices();
            $partiallyPaidIds = self::partiallyPaidInvoices();
            $overdueIds = self::overdueInvoices();
            
            // Combine all excluded IDs
            $excludedIds = array_unique(array_merge($paidIds, $partiallyPaidIds, $overdueIds));
            
            // Get all invoice IDs not in the excluded list
            return self::whereNotIn('id', $excludedIds)
                ->pluck('id')
                ->toArray();
        });
    }
    
    /**
     * Get IDs of overdue invoices
     */
    public static function overdueInvoices(): array
    {
        return cache()->remember('overdue_invoice_ids', self::CACHE_DURATION, function() {
            $paidIds = self::paidInvoices();
            
            // Get all invoices that are not paid, older than 30 days, and have a balance
            return self::select('invoices.id')
                ->leftJoin('invoice_payments', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('invoice_taxes', 'invoices.id', '=', 'invoice_taxes.invoice_id')
                ->where('invoices.created_at', '<=', now()->subDays(30))
                ->whereNotIn('invoices.id', $paidIds)
                ->groupBy('invoices.id')
                ->havingRaw('COALESCE(SUM(invoice_payments.amount_paid), 0) < (
                    COALESCE(SUM(invoice_details.quantity * invoice_details.unit_price), 0) * 
                    (1 + COALESCE(SUM(invoice_taxes.percentage_tax / 100), 0))
                )')
                ->pluck('invoices.id')
                ->toArray();
        });
    }

    /**
     * Get the encrypted ID for this invoice
     *
     * @return string
     */
    public function getHashedIdAttribute()
    {
        return IdHasher::encode($this->id);
    }

    /**
     * Find an invoice by its encrypted ID
     *
     * @param string $hashedId The hashed invoice ID
     * @return self|null
     */
    public static function findByHashedId(string $hashedId): ?self
    {
        $id = IdHasher::decode($hashedId);
        return $id ? self::with(['details', 'payments', 'taxinvoices'])->find($id) : null;
    }

    /**
     * Remove empty invoice details
     * 
     * @return $this
     */
    public function removeEmpty(): self
    {
        $this->details = $this->details->filter(function($detail) {
            return !empty($detail->description) && 
                   !empty($detail->quantity) && 
                   !empty($detail->unit_price);
        })->values();
        
        return $this;
    }

    /**
     * Convert a number to its word representation
     * 
     * @param float|int|string $number The number to convert
     * @return string The number in words
     */
    public static function numberToWords($number): string
    {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        
        $dictionary = [
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
            5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
            14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
            18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy',
            80 => 'eighty', 90 => 'ninety', 100 => 'hundred', 1000 => 'thousand',
            1000000 => 'million', 1000000000 => 'billion', 1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion', 1000000000000000000 => 'quintillion'
        ];

        if (!is_numeric($number)) {
            return '';
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            trigger_error(
                'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return '';
        }

        if ($number < 0) {
            return $negative . self::numberToWords(abs($number));
        }


        $string = $fraction = '';

        if (strpos((string)$number, '.') !== false) {
            list($number, $fraction) = explode('.', (string)$number, 2);
        }

        $number = (int)$number;

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = (int)($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::numberToWords($remainder);
                }
                break;
            default:
                $baseUnit = (int)pow(1000, (int)floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::numberToWords($remainder);
                }
                break;
        }

        if ($fraction !== '' && is_numeric($fraction)) {
            $string .= $decimal;
            $words = [];
            foreach (str_split((string)$fraction) as $digit) {
                $words[] = $dictionary[(int)$digit];
            }
            $string .= implode(' ', $words);
        }

        return ucfirst(trim($string));
    }
}
