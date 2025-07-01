<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ ucfirst($quotation->transaction_type) }} #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* Import Roboto font with all weights */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap');

        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Ensure all text is black for printing */
        * {
            color: #000000 !important;
            text-shadow: none !important;
        }
        
        /* Force background colors and images */
        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Page setup */
        @page {
            margin: 0.5cm;
            size: A4 portrait;
            marks: none;
            margin-header: 0;
            margin-footer: 0;
        }
        .no-print {
            display: block;
        }
        /* Base body styles */
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Print-specific styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            /* Page setup for printing */
            @page {
                margin: 0.5cm;
                size: A4;
                marks: none;
            }
            
            body {
                margin: 0.5cm !important;
                padding: 0 !important;
                background: white !important;
            }
            /* Chrome/Safari/Edge specific */
            @page {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
            }
            /* Firefox specific */
            @-moz-document url-prefix() {
                @page {
                    margin: 0;
                }
                body {
                    margin: 0.5cm !important;
                }
            }
            @page :left {
                margin: 0.5cm;
                margin-header: 0;
                margin-footer: 0;
            }
            @page :right {
                margin: 0.5cm;
                margin-header: 0;
                margin-footer: 0;
            }
            @page :first {
                margin-top: 0.5cm;
                margin-header: 0;
                margin-footer: 0;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0;
                padding: 0;
            }
            /* Hide URL and other browser-generated content */
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
        /* Hide system links */
        a[href]:after {
            content: none !important;
        }
        /* Hide default headers and footers */
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }
        /* Hide browser headers/footers */
        @page {
            size: auto;
            margin: 0mm;
        }
        body {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.2.4;
            color: #333;
            background-color: white !important;
        }
        .header {
            border-bottom: 1px solid #0F52BA;
            padding-bottom: 5px;
            margin-bottom: 10px;
            position: relative;
            text-align: center;
        }
        .header img {
            width: auto;
            max-width: 150px;
            max-height: 60px;
            object-fit: contain;
            margin: 5px 0;
            display: block;
        }
        .page-info {
            text-align: right;
            margin-bottom: 10px;
            font-size: 10px;
        }
        .tin-number {
            text-align: right;
            margin-bottom: 5px;
            margin-right: 15px;
        }
        .invoice-title {
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            margin: 10px 0;
            color: #0F52BA;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        .consignee-box {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            background-color: #f9f9f9;
            line-height: 1.2.2;
        }
        .consignee-box strong {
            display: block;
            margin-bottom: 2px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 3px;
            line-height: 1.2.2;
        }
        .details-grid strong {
            font-weight: normal;
            padding-right: 10px;
        }
        .details-info {
            width: 100%;
            margin-bottom: 15px;
        }
        .details-info td {
            padding: 5px;
            border: none;
        }
        .details-info strong {
            font-weight: bold;
            padding-right: 10px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details-table th {
            background-color: #f5f5f5;
            font-weight: 500;
        }
        .details-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .details-table th {
            background-color: #fff;
            font-weight: normal;
        }
        .details-table th strong {
            font-weight: bold;
        }
        .amount-section {
            text-align: right;
            margin: 15px 0;
        }
        .amount-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .amount-section td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .amount-section tr:last-child td {
            font-weight: 500;
            background-color: #f5f5f5;
        }
        .bank-details {
            margin-top: 20px;
            font-size: 10px;
        }
        .bank-note {
            font-style: italic;
            margin-top: 5px;
            font-size: 9px;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 33.33%;
            text-align: center;
            border-top: 1px solid #0F52BA;
            padding-top: 8px;
            color: #555;
            font-size: 11px;
        }
        .description-row td {
            border: 1px solid #000;
            padding: 5px;
        }

        /* New styles for professional sections */
        .document-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .customer-info {
            width: 50%;
        }
        .document-meta {
            width: 45%;
        }
        .section-title {
            font-weight: 500;
            color: #0F52BA;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        .customer-name {
            font-weight: 500;
            margin-bottom: 3px;
        }
        .customer-address {
            line-height: 1.2.3;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .meta-table td:first-child {
            width: 35%;
        }
        .shipping-details {
            margin-bottom: 20px;
        }
        .shipping-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .shipping-table td {
            padding: 5px;
            border: none;
            line-height: 1.2.3;
        }
        .shipping-table tr:not(:last-child) td {
            padding-bottom: 8px;
        }
    </style>
    <script>
        function printDocument() {
            // Add print styles
            const css = `
                @page { margin: 0 !important; size: auto; }
                body { margin: 0.5cm !important; }
            `;
            const style = document.createElement('style');
            style.id = 'print-styles';
            style.innerHTML = css;
            document.head.appendChild(style);

            // Print the document
            window.print();

            // Remove the style after printing
            setTimeout(() => {
                if (document.getElementById('print-styles')) {
                    document.head.removeChild(style);
                }
            }, 500);
        }

        // Only print automatically if explicitly requested via URL parameter
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === 'true') {
                // When loaded in an iframe for direct printing, print immediately
                setTimeout(printDocument, 500);
            }
        };
    </script>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/app-assets/img/logo/sifcargo_icon.png'))) }}" alt="SIFCARGO TONIGHT" style="width: auto; max-width: 650px; max-height: 150px; margin: 5px auto; display: block;">
    </div>

    <div class="tin-number" style="margin-bottom: 2px;">TIN: 1033561397</div>

    <div class="invoice-title" style="margin: 2px 0 5px; font-size: 14px;">{{ $quotation->transaction_type === 'invoice' ? 'Invoice' : 'Quotation' }}</div>

    <div class="document-header" style="margin-bottom: 10px; display: flex; justify-content: space-between;">
        <!-- Client Information Box -->
        <div class="customer-info" style="width: 48%; vertical-align: top; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #0F52BA; border-radius: 4px;">
            <div class="section-title" style="font-weight: bold; margin-bottom: 5px; font-size: 14px; color: #0F52BA; text-transform: uppercase;">Client Information</div>
            <div class="customer-name" style="margin-bottom: 3px; font-weight: bold; font-size: 14px;">{{ $quotation->customer->name }}</div>
            <div class="customer-address" style="font-size: 12px; line-height: 1.3;">{{ $quotation->customer->address }}</div>
        </div>

        <!-- Document Details Box -->
        <div class="document-meta" style="width: 48%; vertical-align: top; padding: 10px; background-color: #f9f9f9; border-radius: 4px; border: 1px solid #eee;">
            <div class="section-title" style="font-weight: bold; margin-bottom: 5px; font-size: 14px; color: #333; text-transform: uppercase;">Document Details</div>
            <table class="meta-table" style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <tr style="line-height: 1.2;">
                    <td style="padding: 2px 0; width: 40%;"><strong>Document #:</strong></td>
                    <td style="padding: 2px 0; text-align: right;">{{ ($quotation->transaction_type === 'invoice' ? 'INV' : 'QUO') }}{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</td>
                </tr>
                <tr style="line-height: 1.2;">
                    <td style="padding: 2px 0;"><strong>Date:</strong></td>
                    <td style="padding: 2px 0; text-align: right;">{{ \Carbon\Carbon::parse($quotation->date_quoted)->format('M d, Y') }}</td>
                </tr>
                <tr style="line-height: 1.2;">
                    <td style="padding: 2px 0;"><strong>Currency:</strong></td>
                    <td style="padding: 2px 0; text-align: right;">{{ $quotation->transaction_currency }}</td>
                </tr>
                @if($quotation->reference)
                <tr style="line-height: 1.2;">
                    <td style="padding: 2px 0;"><strong>Reference:</strong></td>
                    <td style="padding: 2px 0; text-align: right;">{{ $quotation->reference }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="shipping-details" style="margin: 10px 0; padding: 10px; background-color: #f9f9f9; border-radius: 4px; border: 1px solid #eee;">
        <div class="section-title" style="font-weight: bold; margin-bottom: 5px; font-size: 14px; color: #333; text-transform: uppercase; border-bottom: 2px solid #0F52BA; padding-bottom: 3px; display: inline-block;">Shipping Details</div>
        <table class="shipping-table" style="width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 5px;">
            <tr style="line-height: 1.2;">
                <td style="padding: 2px 0; width: 20%;"><strong>Container No:</strong></td>
                <td style="padding: 2px 0; width: 30%;">{{ $quotation->container_air_no ?? '—' }}</td>
                <td style="padding: 2px 0; width: 20%;"><strong>Entry No / Ref:</strong></td>
                <td style="padding: 2px 0; width: 30%;">
                    {{ $quotation->entry_no ?? '—' }}
                    @if($quotation->reference)
                        / {{ $quotation->reference }}
                    @endif
                </td>
            </tr>
            @if($quotation->description)
            <tr style="line-height: 1.2;">
                <td style="padding: 2px 0; vertical-align: top;"><strong>Remarks:</strong></td>
                <td colspan="3" style="padding: 2px 0;">{{ $quotation->description }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="details-table" style="border-collapse: collapse; margin: 10px 0; font-size: 12px; width: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 6px 4px; width: 4%; border-bottom: 2px solid #0F52BA; text-align: center;"><strong>No.</strong></th>
                <th style="padding: 6px 4px; width: 50%; border-bottom: 2px solid #0F52BA; text-align: left;"><strong>Description</strong></th>
                <th style="padding: 6px 4px; width: 10%; border-bottom: 2px solid #0F52BA; text-align: right;"><strong>Quantity</strong></th>
                <th style="padding: 6px 4px; width: 15%; border-bottom: 2px solid #0F52BA; text-align: right;"><strong>Rate</strong></th>
                <th style="padding: 6px 4px; width: 20%; border-bottom: 2px solid #0F52BA; text-align: right;"><strong>Amount</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->details as $index => $item)
            <tr style="line-height: 1.3; {{ $index % 2 == 0 ? '' : 'background-color: #f9f9f9;' }}">
                <td style="padding: 5px 4px; text-align: center; border-bottom: 1px solid #e0e0e0;">{{ $index + 1 }}</td>
                <td style="padding: 5px 4px; border-bottom: 1px solid #e0e0e0;">{{ $item->particular }}</td>
                <td style="padding: 5px 4px; text-align: right; border-bottom: 1px solid #e0e0e0;">{{ number_format($item->quantity, 0) }}</td>
                <td style="padding: 5px 4px; text-align: right; border-bottom: 1px solid #e0e0e0;">{{ number_format($item->unit_price, 0) }}</td>
                <td style="padding: 5px 4px; text-align: right; border-bottom: 1px solid #e0e0e0;">{{ number_format($item->quantity * $item->unit_price, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: flex; margin: 10px 0;">
        <div style="flex: 1; padding-right: 10px;">
            <div style="margin-bottom: 10px; padding: 8px; background-color: #f9f9f9; border-left: 4px solid #0F52BA; border-radius: 4px;">
                <div style="font-weight: bold; margin-bottom: 3px; font-size: 12px; color: #333; text-transform: uppercase;">Amount in Words:</div>
                <div style="font-size: 12px; color: #0F52BA; font-weight: bold; text-transform: uppercase;">
                    {{ \App\Models\Invoice::numberToWords(round($quotation->details->sum(function($item) { return $item->quantity * $item->unit_price; }))) }} {{ $quotation->transaction_currency }} ONLY
                </div>
            </div>
            
            @if($quotation->transaction_type == 'invoice' && $quotation->payments->count() > 0)
            <div style="margin-top: 15px; padding: 8px; background-color: #f9f9f9; border-left: 4px solid #28a745; border-radius: 4px;">
                <div style="font-weight: bold; margin-bottom: 5px; font-size: 12px; color: #333; text-transform: uppercase; border-bottom: 1px solid #28a745; padding-bottom: 3px;">Payment History</div>
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="padding: 4px; text-align: left; border-bottom: 1px solid #ddd;">Date</th>
                            <th style="padding: 4px; text-align: right; border-bottom: 1px solid #ddd;">Amount</th>
                            <th style="padding: 4px; text-align: left; border-bottom: 1px solid #ddd;">Method</th>
                            <th style="padding: 4px; text-align: left; border-bottom: 1px solid #ddd;">Recorded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotation->payments as $payment)
                        <tr>
                            <td style="padding: 4px; border-bottom: 1px solid #eee;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                            <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;">{{ number_format($payment->amount_paid, 0) }}</td>
                            <td style="padding: 4px; border-bottom: 1px solid #eee;">{{ $payment->mode_of_payment }}</td>
                            <td style="padding: 4px; border-bottom: 1px solid #eee;">{{ $payment->recorder ? $payment->recorder->name : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div style="width: 40%;">
            <table style="border-collapse: collapse; width: 100%; font-size: 12px; margin-left: auto;">
                <tr style="line-height: 1.5;">
                    <td style="padding: 4px; text-align: right; font-weight: bold; border-bottom: 1px solid #e0e0e0;">Subtotal:</td>
                    <td style="padding: 4px; text-align: right; border-bottom: 1px solid #e0e0e0;">{{ number_format($quotation->details->sum(function($item) { return $item->quantity * $item->unit_price; }), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>

                @if($quotation->transaction_type == 'invoice' && $quotation->payments->sum('amount_paid') > 0)
                <tr style="line-height: 1.5;">
                    <td style="padding: 4px; text-align: right; font-weight: bold; border-bottom: 1px solid #e0e0e0;">Amount Paid:</td>
                    <td style="padding: 4px; text-align: right; border-bottom: 1px solid #e0e0e0;">{{ number_format($quotation->payments->sum('amount_paid'), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>
                <tr style="line-height: 1.5;">
                    <td style="padding: 4px; text-align: right; font-weight: bold; color: #0F52BA; border-top: 2px solid #0F52BA; border-bottom: 2px solid #0F52BA;">Balance Due:</td>
                    <td style="padding: 4px; text-align: right; font-weight: bold; color: #FF0000; border-top: 2px solid #0F52BA; border-bottom: 2px solid #0F52BA;">{{ number_format($quotation->details->sum(function($item) { return $item->quantity * $item->unit_price; }) - $quotation->payments->sum('amount_paid'), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>
                @else
                <tr style="line-height: 1.5;">
                    <td style="padding: 4px; text-align: right; font-weight: bold; color: #0F52BA; border-top: 2px solid #0F52BA; border-bottom: 2px solid #0F52BA;">TOTAL:</td>
                    <td style="padding: 4px; text-align: right; font-weight: bold; color: #0F52BA; border-top: 2px solid #0F52BA; border-bottom: 2px solid #0F52BA;">{{ number_format($quotation->details->sum(function($item) { return $item->quantity * $item->unit_price; }), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="bank-details" style="margin-top: 10px; padding: 10px; background-color: #f9f9f9; border-radius: 4px; border: 1px solid #e0e0e0;">
        <div style="font-weight: bold; margin-bottom: 5px; font-size: 12px; color: #333;">Bank Details</div>
        <div style="margin-bottom: 2px; font-size: 12px;"><strong>ACCOUNT NAME:</strong> GRULAND LOGISTICS LTD </div>
        <div style="margin-bottom: 2px; font-size: 12px;"><strong>BANK:</strong> EQUITY BANK</div>
        <div style="margin-bottom: 2px; font-size: 12px;"><strong>ACCOUNT NUMBER (UGX):</strong> 1002201945777</div>
        <div style="margin-bottom: 2px; font-size: 12px;"><strong>ACCOUNT NUMBER (USD):</strong> 1002201945782</div>
        <div style="margin-top: 5px; padding-top: 5px; border-top: 1px solid #e0e0e0; font-style: italic; color: #666; font-size: 10px; line-height: 1.3;">
            All invoices issued by the Company shall be paid by the Client within 2 (two) days of the date of invoice unless otherwise agreed in writing by the Company. Invoices unpaid for more than 30 days after the due date as per invoice will incur a surcharge of 2% per month of the outstanding amount.
        </div>
    </div>

    <table class="signature-table" style="width: 100%; margin-top: 50px; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td style="width: 33.33%; text-align: center; border-top: 2px solid #0F52BA; padding-top: 5px; font-weight: bold;">Prepared by: {{ $quotation->user->name }}</td>
            <td style="width: 33.33%; text-align: center; border-top: 2px solid #0F52BA; padding-top: 5px; font-weight: bold;">Verified by</td>
            <td style="width: 33.33%; text-align: center; border-top: 2px solid #0F52BA; padding-top: 5px; font-weight: bold;">Received by</td>
        </tr>
    </table>

    <!--<div style="margin-top: 10px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #e0e0e0; padding-top: 5px;">
        <div>This is a secure document generated on: {{ date('d M Y H:i:s') }}</div>
        <div>This document is for reference only and does not require a signature.</div>
        <div style="color: #0F52BA; font-weight: bold; margin-top: 5px; font-size: 12px;">SIFCARGO - TONIGHT</div>
    </div> -->

    <div style="text-align: center; margin-top: 10px;" class="no-print">
        <button onclick="printDocument()" class="print-button" style="padding: 8px 16px; background-color: #0F52BA; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold;">
            <i style="margin-right: 5px;">🖨️</i> Print Document
        </button>
    </div>
</body>
</html>
