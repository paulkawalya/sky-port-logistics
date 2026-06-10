<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        @page {
            margin: 1cm; /* Standard margin */
            size: 210mm 297mm; /* A4 exact dimensions */
            marks: none;
            margin-header: 0;
            margin-footer: 0;
        }
        .no-print {
            display: block;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            /* Remove browser headers/footers while keeping document margins */
            @page {
                margin: 0;
                size: A4;
                marks: none;
            }
            body {
                margin: 1cm !important;
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
                    margin: 1cm !important;
                }
            }
            @page :left {
                margin: 1cm;
                margin-header: 0;
                margin-footer: 0;
            }
            @page :right {
                margin: 1cm;
                margin-header: 0;
                margin-footer: 0;
            }
            @page :first {
                margin: 1cm;
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
            font-family: 'Roboto', sans-serif, Arial, Helvetica;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background-color: white !important;
            width: 100%;
            max-width: 190mm; /* A4 width with standard margins */
            /* height: 297mm; /* A4 height */
            margin: 0 auto;
            letter-spacing: 0.1px;
            box-sizing: border-box;
        }
        .header {
            border-bottom: 1px solid #dc0000;
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
        }
        .invoice-title {
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            margin: 10px 0;
            color: #dc0000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .details-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 8px;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 2px;
            text-align: left;
            word-wrap: break-word;
            overflow: hidden;
        }
        .signature-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 33.33%;
            text-align: center;
            border-top: 1px solid #dc0000;
            padding-top: 3px;
            color: #555;
            font-size: 8px;
        }
    </style>
    <script>
        function printDocument() {
            // Add print styles
            const css = `
                @page { margin: 0 !important; size: auto; }
                body { margin: 1cm !important; }
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
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/app-assets/img/logo/skyport.png'))) }}" alt="SKY PORT LOGISTICS LTD" style="width: auto; max-width: 650px; max-height: 150px; margin: 0 auto 5px; display: block;">
        <h3 style="font-weight: bold; font-size: 14px; margin: 5px 0; text-transform: uppercase;">INVOICE</h3>
        <hr style="height: 1px; border: none; background-color: #dc0000; margin: 5px 0 10px;">
    </div>

    <table style="width: 100%; max-width: 190mm; border-collapse: collapse; margin-bottom: 5px; table-layout: fixed;">
        <tr>
            <td style="width: 33%; vertical-align: top; padding-right: 5px;">
                <div style="font-size: 11px;">
                    <h5 style="font-weight: bold; margin: 0 0 2px 0; font-size: 12px;">CONSIGNEE</h5>
                    <div style="margin-bottom: 1px;">{{ $invoice->shippment->customer->name }}</div>
                    <div>{{ $invoice->shippment->customer->phone_number }}</div>
                </div>
            </td>

            <td style="width: 33%; vertical-align: top; padding-right: 5px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <tr style="line-height: 1.5;">
                        <td style="padding: 0 5px 0 0;"><b>Invoice No.</b></td>
                        <td style="padding: 0;">{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr style="line-height: 1.5;">
                        <td style="padding: 0 5px 0 0;"><b>Date: </b></td>
                        <td style="padding: 0;">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>
                    </tr>
                    <tr style="line-height: 1.5;">
                        <td style="padding: 0 5px 0 0;"><b>Currency: </b></td>
                        <td style="padding: 0;">{{ $invoice->transaction_currency }}</td>
                    </tr>
                </table>
            </td>

            <td style="width: 33%; vertical-align: top;">
                <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <tr style="line-height: 1.5;">
                        <td style="padding: 0 5px 0 0;"><b>Container No.</b></td>
                        <td style="padding: 0;">{{ $invoice->shippment->transit->number }}</td>
                    </tr>
                    <tr style="line-height: 1.5;">
                        <td style="padding: 0 5px 0 0;"><b>Volume: </b></td>
                        <td style="padding: 0;">{{ $invoice->shippment->volume }} {{ $invoice->shippment->unit_of_measure }}</td>
                    </tr>
                    <tr style="line-height: 1.5;">
                        <td style="padding: 0 5px 0 0;"><b>Packages: </b></td>
                        <td style="padding: 0;">{{ $invoice->shippment->bl_packages }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1px solid #ddd; margin: 5px 0;">

    <table class="details-table" style="border-collapse: collapse; margin: 10px 0; font-size: 11px; table-layout: fixed; width: 100%; max-width: 190mm; overflow: hidden;">
        <thead>
            <tr style="line-height: 1.5; background-color: #f8f9fa;">
                <th style="padding: 5px; width: 5%; border: 1px solid #ddd;"><strong>No.</strong></th>
                <th style="padding: 5px; width: 45%; border: 1px solid #ddd;"><strong>Description</strong></th>
                <th style="padding: 5px; text-align: right; width: 10%; border: 1px solid #ddd;"><strong>Qty</strong></th>
                <th style="padding: 5px; text-align: right; width: 15%; border: 1px solid #ddd;"><strong>Unit</strong></th>
                <th style="padding: 5px; text-align: right; width: 15%; border: 1px solid #ddd;"><strong>Amount</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->details as $index => $item)
            <tr style="line-height: 1.5;">
                <td style="padding: 5px; width: 5%; border: 1px solid #ddd;">{{ $index + 1 }}</td>
                <td style="padding: 5px; width: 45%; border: 1px solid #ddd;">{{ $item->particular }}</td>
                <td style="padding: 5px; text-align: right; width: 10%; border: 1px solid #ddd;">{{ $item->quantity }}</td>
                <td style="padding: 5px; text-align: right; width: 15%; border: 1px solid #ddd;">{{ $item->unit_price }}</td>
                <td style="padding: 5px; text-align: right; width: 15%; border: 1px solid #ddd;">{{ number_format($item->quantity * $item->unit_price) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin: 10px 0;">
        <div style="margin-bottom: 10px; font-size: 11px;">
            <b>AMOUNT IN WORDS: </b>
            <span style="text-transform: uppercase; color: #dc0000; font-weight: bold;">{{ \App\Models\Invoice::numberToWords(round($totalAmount)) }} {{ $invoice->transaction_currency ?? 'UGX' }} ONLY</span>
        </div>

        <div style="display: flex; max-width: 190mm;">
            <div style="width: 55%;"></div>
            <div style="width: 45%;">
                <table style="border-collapse: collapse; width: 100%; font-size: 11px; table-layout: fixed;">
                    <tr style="line-height: 1.5;">
                        <td style="padding: 4px; text-align: right; width: 65%; border-bottom: 1px solid #eee;"><b>Amount: </b></td>
                        <td style="padding: 4px; text-align: right; width: 35%; border-bottom: 1px solid #eee;">{{ number_format($amount, 0) }}</td>
                    </tr>
                    @foreach($invoice->taxinvoices as $taxinvoice)
                    <tr style="line-height: 1.5;">
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;"><b>{{ $taxinvoice->tax->tax_name }}: </b></td>
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;">{{ number_format($taxinvoice->amount($taxinvoice->id), 0) }}</td>
                    </tr>
                    @endforeach
                    <tr style="line-height: 1.5;">
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;"><b>Sub Total</b></td>
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;">{{ number_format($amount, 0) }}</td>
                    </tr>
                    <tr style="line-height: 1.5;">
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;"><b>Taxes</b></td>
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;">{{ number_format($taxAmount, 0) }}</td>
                    </tr>
                    <tr style="line-height: 1.5;">
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;"><b>Total</b></td>
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;">{{ number_format($totalAmount, 0) }}</td>
                    </tr>
                    @if($paidAmount > 0)
                    <tr style="line-height: 1.5;">
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;"><b>Paid</b></td>
                        <td style="padding: 4px; text-align: right; border-bottom: 1px solid #eee;">{{ number_format($paidAmount, 0) }}</td>
                    </tr>
                    <tr style="line-height: 1.5; background-color: #f8f9fa;">
                        <td style="padding: 4px; text-align: right;"><b>Balance</b></td>
                        <td style="padding: 4px; text-align: right; font-weight: bold;">{{ number_format($balance, 0) }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
        <div style="font-size: 10px; margin-bottom: 10px;">
            <strong style="color: #555;">Bank Details</strong><br>
            ACCOUNT NAME: SKY PORT LOGISTICS LIMITED<br>
            BANK: DFCU - BANK<br>
            BRANCH: JINJA - ROAD BRANCH<br>
            ACCOUNT NUMBER (UGX): 01070019884752<br>
            ACCOUNT NUMBER (USD): 01070019884899<br>
            <div style="font-size: 9px; font-style: italic; margin-top: 5px; color: #666;">
                All invoices issued by the Company shall be paid by the Client within 2 (two) days of the date of invoice unless otherwise agreed in writing by the Company. Invoices unpaid for more than 30 days after the due date as per invoice will incur a surcharge of 2% per month of the outstanding amount.
            </div>
        </div>
    </div>

    <table style="width: 100%; margin-top: 10px; border-collapse: collapse; font-size: 10px;">
        <tr>
            <td style="width: 33.33%; text-align: left; padding: 5px 0;"><b>Processed by: </b></td>
            <td style="width: 33.33%; text-align: left; padding: 5px 0;"><b>Verified by</b></td>
            <td style="width: 33.33%; text-align: left; padding: 5px 0;"><b>Approved by: </b></td>
        </tr>
        <tr>
            <td style="width: 33.33%; text-align: left; padding: 5px 0;"><span style="background-color: #f8f9fa; padding: 2px 5px; border-radius: 3px; display: inline-block;">{{ $invoice->user->name }}</span></td>
            <td style="width: 33.33%; text-align: left; padding: 5px 0;"><span style="font-size: 10px; font-style: italic; color: #666;">Accountant</span></td>
            <td style="width: 33.33%; text-align: left; padding: 5px 0;"></td>
        </tr>
    </table>
    <div style="text-align: center; margin-top: 20px;" class="no-print">
        <button onclick="printDocument()" class="print-button" style="padding: 8px 16px; background-color: #dc0000; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Print Document
        </button>
    </div>
</body>
</html>
