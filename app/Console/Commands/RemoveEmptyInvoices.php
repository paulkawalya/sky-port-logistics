<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RemoveEmptyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:remove-empty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all empty invoices from the database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to remove empty invoices...');
        
        try {
            // Find invoices with no details or payments
            $emptyInvoices = DB::table('invoices')
                ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->leftJoin('invoice_payments', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->groupBy('invoices.id')
                ->havingRaw('COUNT(invoice_details.id) = 0')
                ->havingRaw('COUNT(invoice_payments.id) = 0')
                ->pluck('invoices.id');

            $count = $emptyInvoices->count();
            
            if ($count > 0) {
                $this->info("Found $count empty invoices to remove...");
                
                // Delete in chunks to avoid memory issues
                $emptyInvoices->chunk(1000, function ($chunk) {
                    DB::table('invoices')->whereIn('id', $chunk)->delete();
                });
                
                $this->info("Successfully removed $count empty invoices.");
            } else {
                $this->info('No empty invoices found.');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Error removing empty invoices: ' . $e->getMessage());
            Log::error('Error in RemoveEmptyInvoices command: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
