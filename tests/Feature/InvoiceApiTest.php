<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceApiTest extends TestCase
{
    /**
     * Test the invoice listing API with payment status filter.
     *
     * @return void
     */
    public function test_invoice_listing_with_payment_status_filter()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Act as the user
        $this->actingAs($user);
        
        // Test all invoices
        $response = $this->getJson('/api/invoices');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'pagination' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'from',
                'to',
            ],
        ]);
        
        // Test paid invoices
        $response = $this->getJson('/api/invoices?payment_status=paid');
        $response->assertStatus(200);
        
        // Test pending invoices
        $response = $this->getJson('/api/invoices?payment_status=pending');
        $response->assertStatus(200);
    }
    
    /**
     * Test the invoice statistics API.
     *
     * @return void
     */
    public function test_invoice_statistics()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Act as the user
        $this->actingAs($user);
        
        // Test statistics endpoint
        $response = $this->getJson('/api/invoices/statistics');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'total_invoices',
                'paid_invoices',
                'pending_invoices',
                'amounts' => [
                    'usd' => [
                        'total',
                        'paid',
                        'pending',
                    ],
                    'ugx' => [
                        'total',
                        'paid',
                        'pending',
                    ],
                ],
            ],
        ]);
    }
    
    /**
     * Test the invoice detail API.
     *
     * @return void
     */
    public function test_invoice_detail()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Act as the user
        $this->actingAs($user);
        
        // Get the first invoice if any exists
        $invoice = Invoice::first();
        
        if ($invoice) {
            $response = $this->getJson('/api/invoices/' . $invoice->id);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'data' => [
                    'id',
                    'hashed_id',
                    'date_invoiced',
                    'transaction_currency',
                    'amount',
                    'total_amount',
                    'paid_amount',
                    'balance',
                    'payment_status',
                    'details',
                    'payments',
                    'taxes',
                ],
            ]);
        } else {
            // Skip the test if no invoices exist
            $this->markTestSkipped('No invoices available for testing.');
        }
    }
}
