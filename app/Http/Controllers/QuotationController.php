<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Quotation;
use App\Models\QuotationPayment;
use App\Models\Tax;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $quotations = Quotation::with(['customer', 'user'])
            ->orderBy('id', 'desc')
            ->get();

        $customers = Customer::get();

        $transaction_currencies = ["USD","UGX"];

        $transaction_types = ['invoice','quotation'];

        $data = [
            'quotations'=>$quotations,
            'title'=>'Manual Invoices',
            'customers'=>$customers,
            'transaction_currencies'=>$transaction_currencies,
            'transaction_types'=>$transaction_types,
        ];

        return view('quotation.quotations')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'customer_id' => 'required',
            'date_quoted' => 'required|date',
            'transaction_currency' => 'required',
            'transaction_type' => 'required',
            'container_air_no' => 'nullable|string|max:255',
            'entry_no' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'volume' => 'nullable|numeric|min:0',
            'packages' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000'
        ];

        $validated = $request->validate($rules);

        try {
            $quotation = Quotation::create([
                'user_id' => Auth::id(),
                'customer_id' => $validated['customer_id'],
                'date_quoted' => $validated['date_quoted'],
                'transaction_type' => $validated['transaction_type'],
                'transaction_currency' => $validated['transaction_currency'],
                'container_air_no' => $validated['container_air_no'],
                'entry_no' => $validated['entry_no'],
                'weight' => $validated['weight'],
                'volume' => $validated['volume'],
                'packages' => $validated['packages'],
                'description' => $validated['description']
            ]);

            if ($request->wantsJson()) {
                $documentType = $validated['transaction_type'] === 'invoice' ? 'Invoice' : 'Quotation';
                return response()->json([
                    'success' => true,
                    'message' => $documentType . ' created successfully',
                    'redirect' => route('quotations.show', $quotation->id)
                ]);
            }

                return redirect()->route('quotations.show', $quotation->id);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating quotation: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error creating quotation']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Quotation $quotation)
    {

        $mode_of_payments = ['Cash','Bank','Mobile Money','Digital'];

        $taxes = Tax::where('currency',$quotation->transaction_currency)->get();

        $data = [
            'quotation'=>$quotation,
            'title'=>'Add Items to Invoice',
            'mode_of_payments'=>$mode_of_payments,
            'taxes'=>$taxes
        ];

        return view('quotation.quotation_details')->with($data);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quotation $quotation)
    {
        $mode_of_payments = ['Cash','Bank','Mobile Money','Digital'];

        $data = [
            'quotation'=>$quotation,
            'title'=>'Add Items to Invoice',
            'mode_of_payments'=>$mode_of_payments,
        ];

        return view('quotation.print_quotation_details')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
        $rules = [
            'customer_id' => 'required',
            'date_quoted' => 'required|date',
            'transaction_currency' => 'required',
            'transaction_type' => 'required',
            'container_air_no' => 'nullable|string|max:255',
            'entry_no' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'volume' => 'nullable|numeric|min:0',
            'packages' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000'
        ];

        $validated = $request->validate($rules);

        try {
            $quotation->update([
                'customer_id' => $validated['customer_id'],
                'date_quoted' => $validated['date_quoted'],
                'transaction_type' => $validated['transaction_type'],
                'transaction_currency' => $validated['transaction_currency'],
                'container_air_no' => $validated['container_air_no'],
                'entry_no' => $validated['entry_no'],
                'weight' => $validated['weight'],
                'volume' => $validated['volume'],
                'packages' => $validated['packages'],
                'description' => $validated['description']
            ]);

            if ($request->wantsJson()) {
                $documentType = $validated['transaction_type'] === 'invoice' ? 'Invoice' : 'Quotation';
                return response()->json([
                    'success' => true,
                    'message' => $documentType . ' updated successfully',
                    'redirect' => route('quotations.show', $quotation->id)
                ]);
            }

            return redirect()->route('quotations.show', $quotation->id);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating quotation: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error updating quotation']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($quotation_id)
    {
        Quotation::destroy($quotation_id);

        return redirect()->route('quotations.index');
    }

    /**
     * Print the quotation/invoice
     */
    public function print($id)
    {
        $quotation = Quotation::with(['customer', 'details', 'taxquotations.tax', 'payments.user', 'user'])->findOrFail($id);
        return view('quotation.print', compact('quotation'));
    }

    /**
     * Show print preview for the quotation
     */
    public function printQuotation($id)
    {
        $quotation = Quotation::with(['customer', 'details', 'taxquotations.tax', 'payments.user', 'user'])->findOrFail($id);
        return view('quotation.print', compact('quotation'));
    }

    /**
     * Generate PDF version of the quotation/invoice
     */
    public function generatePdf($id)
    {
        $quotation = Quotation::with(['customer', 'details', 'taxquotations.tax', 'payments.user', 'user'])->findOrFail($id);

        // Configure PDF options for better print-like output
        $options = [
            'dpi' => 300,
            'defaultFont' => 'Roboto',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'margin_top' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'enable_php' => true,
            'orientation' => 'portrait',
            'enable_javascript' => true,
            'javascript_delay' => 2000, // Increased delay to ensure all content is rendered
            'no-outline' => true,
            'encoding' => 'UTF-8',
            'enable_smart_shrinking' => false,
            'images' => true,
            'use_print_media' => true,
            'chroot' => public_path(),
            'enable_local_file_access' => true,
            'zoom' => 1.0,
            'disable-smart-shrinking' => true,
            'print-media-type' => true,
            'page-size' => 'A4',
            'viewport-size' => '1024x768',
            'disable-internal-links' => false,
            'disable-external-links' => false,
            'background' => true,
            'no-background' => false,
            'load-media-error-handling' => 'ignore',
            'load-error-handling' => 'ignore'
        ];


        // Load the view and generate PDF
        $pdf = PDF::loadView('quotation.print', compact('quotation'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions($options);

        // Set the filename
        $filename = $quotation->transaction_type . '_' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) . '.pdf';

        // Return the PDF as a download
        return $pdf->download($filename);
    }

    /**
     * Mark a quotation as paid
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function markAsPaid($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);

            // Check if this is an invoice type quotation
            if ($quotation->transaction_type !== 'invoice') {
                return back()->with('error', 'Only invoices can be marked as paid.');
            }

            // Calculate the remaining balance
            $balance = $quotation->balance($quotation->id);

            if ($balance <= 0) {
                return back()->with('info', 'This invoice is already fully paid.');
            }

            // Create a payment record for the remaining balance
            $payment = new QuotationPayment();
            $payment->quotation_id = $quotation->id;
            $payment->user_id = Auth::id();
            $payment->payment_date = now();
            $payment->amount_paid = $balance;
            $payment->mode_of_payment = 'System';
            $payment->narrative = 'Marked as paid by ' . Auth::user()->name;
            $payment->save();

            return redirect()->route('quotations.show', $quotation->id)
                ->with('success', 'Invoice has been marked as paid successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error marking invoice as paid: ' . $e->getMessage());
        }
    }

    /**
     * Generate a public PDF for quotation that doesn't require authentication
     *
     * @param string $hashedId
     * @return \Illuminate\Http\Response
     */
    public function publicPdf($hashedId)
    {
        try {
            \Log::info('Starting PDF generation for hash: ' . $hashedId);
            $quotation = null;

            // Method 1: Try direct ID if numeric
            if (is_numeric($hashedId)) {
                \Log::info('Trying direct ID lookup for: ' . $hashedId);
                $quotation = Quotation::with([
                    'customer',
                    'details',
                    'taxquotations.tax',
                    'payments.user',
                    'user'
                ])->find($hashedId);
                \Log::info('Direct lookup ' . ($quotation ? 'succeeded' : 'failed'));
            } 
            
            // Method 2: Try findByHash if exists
            if (!$quotation && method_exists(Quotation::class, 'findByHash')) {
                \Log::info('Trying findByHash for: ' . $hashedId);
                $quotation = Quotation::findByHash($hashedId);
                \Log::info('findByHash ' . ($quotation ? 'succeeded' : 'failed'));
            }
            
            // Method 3: Try IdHasher if exists
            if (!$quotation && class_exists('\\App\\Helpers\\IdHasher')) {
                \Log::info('Trying IdHasher for: ' . $hashedId);
                try {
                    $id = \App\Helpers\IdHasher::decode($hashedId);
                    if ($id) {
                        $quotation = Quotation::with([
                            'customer',
                            'details',
                            'taxquotations.tax',
                            'payments.user',
                            'user'
                        ])->find($id);
                        \Log::info('IdHasher lookup ' . ($quotation ? 'succeeded' : 'failed'));
                    }
                } catch (\Exception $e) {
                    \Log::error('Error in IdHasher: ' . $e->getMessage());
                }
            }

            if (!$quotation) {
                $error = 'Quotation not found for hash: ' . $hashedId;
                \Log::error($error);
                abort(404, $error);
            }

            \Log::info('Found quotation #' . $quotation->id . ', now generating PDF');
            $quotation->is_public = true;

            // Check if we're in production
            $isProduction = app()->environment('production');
            
            // Set temporary directory for dompdf
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            
            // Set the temporary directory for dompdf
            if (!defined('DOMPDF_TEMP_DIR')) {
                define('DOMPDF_TEMP_DIR', $tempDir);
            }
            if (!defined('DOMPDF_CHROOT')) {
                define('DOMPDF_CHROOT', base_path());
            }

            // Generate HTML first to catch view errors
            $html = '';
            try {
                $html = view('quotation.pdf_template', compact('quotation'))->render();
                \Log::info('Successfully rendered PDF template');
            } catch (\Exception $e) {
                \Log::error('Error rendering PDF template: ' . $e->getMessage());
                throw new \Exception('Error rendering PDF template: ' . $e->getMessage());
            }

            // Generate PDF with appropriate options
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'dejavu sans',
                    'dpi' => 96,
                    'enable_php' => false,
                    'isPhpEnabled' => true,
                    'isJavascriptEnabled' => false,
                    'debugKeepTemp' => false, // Disable in production
                    'tempDir' => $tempDir,
                    'chroot' => base_path(),
                    'logOutputFile' => $isProduction ? storage_path('logs/dompdf.log') : '',
                    'defaultMediaType' => 'print',
                    'isFontSubsettingEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

                $pdf->loadHtml($html);
                $pdf->setPaper('A4', 'portrait');

                $filename = $quotation->transaction_type . '_' . str_pad($quotation->id, 4, '0', STR_PAD_LEFT) . '.pdf';
                
                \Log::info('PDF generation completed successfully');
                
                return $pdf->stream($filename, [
                    'Attachment' => false,
                    'isRemoteEnabled' => true,
                    'Content-Disposition' => 'inline; filename="' . $filename . '"'
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Error generating PDF: ' . $e->getMessage());
                \Log::error($e->getTraceAsString());
                
                // Try a fallback method if the main one fails
                if ($isProduction) {
                    try {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotation.pdf_template', compact('quotation'));
                        return $pdf->stream($filename, [
                            'Attachment' => false,
                            'isRemoteEnabled' => false
                        ]);
                    } catch (\Exception $fallbackError) {
                        \Log::error('Fallback PDF generation also failed: ' . $fallbackError->getMessage());
                    }
                }
                
                throw new \Exception('Failed to generate PDF: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            // Return a more user-friendly error in production
            if (app()->environment('production')) {
                return response()->view('errors.500', [
                    'message' => 'We encountered an error while generating the PDF. Please try again later.'
                ], 500);
            }
            
            // In development, show the full error
            throw $e;
        }
    }
}
