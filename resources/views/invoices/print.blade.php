<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        
        @page {
            margin: 1.5cm;
            size: A4;
            marks: none;
            margin-header: 0;
            margin-footer: 0;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            @page {
                margin: 0;
                size: A4;
                marks: none;
            }
            body {
                margin: 1.5cm !important;
            }
            @page {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
            }
            @-moz-document url-prefix() {
                @page {
                    margin: 0;
                }
                body {
                    margin: 1.5cm !important;
                }
            }
            @page :left {
                margin: 1.5cm;
                margin-header: 0;
                margin-footer: 0;
            }
            @page :right {
                margin: 1.5cm;
                margin-header: 0;
                margin-footer: 0;
            }
            @page :first {
                margin-top: 1.5cm;
                margin-header: 0;
                margin-footer: 0;
            }
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0;
                padding: 0;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
        a[href]:after {
            content: none !important;
        }
        thead {
            display: table-header-group;
        }
        tfoot {
            display: table-footer-group;
        }
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
            line-height: 1.4;
            color: #333;
            background-color: white !important;
        }
        .header {
            border-bottom: 2px solid #dc0000;
            padding-bottom: 15px;
            margin-bottom: 25px;
            position: relative;
        }
        .header img {
            width: 100%;
            max-height: 120px;
            object-fit: contain;
            margin: 10px 0;
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
            font-size: 18px;
            font-weight: 500;
            margin: 20px 0;
            color: #dc0000;
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
            line-height: 1.2;
        }
        .consignee-box strong {
            display: block;
            margin-bottom: 2px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 3px;
            line-height: 1.2;
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
        .signatures {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }
        .signature-line {
            border-top: 1px solid #dc0000;
            padding-top: 8px;
            text-align: center;
            margin-top: 30px;
            color: #555;
            font-size: 11px;
        }
        .description-row td {
            border: 1px solid #000;
            padding: 5px;
        }
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
            color: #dc0000;
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
            line-height: 1.3;
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
            line-height: 1.3;
        }
        .shipping-table tr:not(:last-child) td {
            padding-bottom: 8px;
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() {
                const css = `
                    @page { margin: 0 !important; size: auto; }
                    body { margin: 1.5cm !important; }
                `;
                const style = document.createElement('style');
                style.id = 'print-styles';
                style.innerHTML = css;
                document.head.appendChild(style);

                setTimeout(() => {
                    window.print();
                    
                    setTimeout(() => {
                        if (window.chrome) {
                            document.head.removeChild(style);
                            window.print();
                        }
                    }, 500);
                }, 200);
            }, 500);
        };
    </script>
</head>
<body>
    <div class="header">
        <img src="{{ asset('/app-assets/img/logo/skyport.png') }}" alt="SKY PORT LOGISTICS LTD">
    </div>

    <div class="tin-number" style="margin-bottom: 2px;">TIN: 1033561397</div>

    <div class="invoice-title" style="margin: 2px 0 5px;">INVOICE</div>

    <div class="document-header" style="margin-bottom: 10px;">
        <div class="customer-info" style="display: inline-block; width: 50%; vertical-align: top;">
            <div class="section-title" style="font-weight: bold; margin-bottom: 2px;">BILL TO</div>
            <div class="customer-name" style="margin-bottom: 2px;">{{ $invoice->shippment->customer->name }}</div>
            <div class="customer-address">{{ $invoice->shippment->customer->address }}</div>
        </div>
        
        <div class="document-meta" style="display: inline-block; width: 45%; vertical-align: top;">
            <table class="meta-table" style="width: 100%; border-collapse: collapse;">
                <tr style="line-height: 1.1;">
                    <td style="padding: 1px 0;"><strong>INVOICE #</strong></td>
                    <td style="padding: 1px 0;">INV{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</td>
                </tr>
                <tr style="line-height: 1.1;">
                    <td style="padding: 1px 0;"><strong>DATE</strong></td>
                    <td style="padding: 1px 0;">{{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}</td>
                </tr>
                <tr style="line-height: 1.1;">
                    <td style="padding: 1px 0;"><strong>CURRENCY</strong></td>
                    <td style="padding: 1px 0;">{{ $invoice->transaction_currency }}</td>
                </tr>
                @if($invoice->shippment->reference)
                <tr style="line-height: 1.1;">
                    <td style="padding: 1px 0;"><strong>REFERENCE</strong></td>
                    <td style="padding: 1px 0;">{{ $invoice->shippment->reference }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="shipping-details" style="margin-top: 10px;">
        <div class="section-title" style="font-weight: bold; margin-bottom: 3px;">SHIPPING DETAILS</div>
        <table class="shipping-table" style="width: 100%; border-collapse: collapse;">
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 0; width: 20%;"><strong>CONTAINER NO.</strong></td>
                <td style="padding: 1px 0; width: 30%;">{{ $invoice->shippment->transit->number ?? '—' }}</td>
                <td style="padding: 1px 0; width: 20%;"><strong>ENTRY NO.</strong></td>
                <td style="padding: 1px 0; width: 30%;">{{ $invoice->shippment->entry_no ?? '—' }}</td>
            </tr>
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 0;"><strong>WEIGHT (KG)</strong></td>
                <td style="padding: 1px 0;">{{ $invoice->shippment->weight ? number_format($invoice->shippment->weight, 3) : '0.000' }}</td>
                <td style="padding: 1px 0;"><strong>VOLUME</strong></td>
                <td style="padding: 1px 0;">{{ $invoice->shippment->volume ? number_format($invoice->shippment->volume, 3) : '0.000' }}</td>
            </tr>
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 0;"><strong>PACKAGES</strong></td>
                <td style="padding: 1px 0;">{{ $invoice->shippment->bl_packages ?? '0' }}</td>
                <td style="padding: 1px 0;"><strong>REFERENCE</strong></td>
                <td style="padding: 1px 0;"></td>
            </tr>
            @if($invoice->shippment->description)
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 0;"><strong>REMARKS</strong></td>
                <td colspan="3" style="padding: 1px 0;">{{ $invoice->shippment->description }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="details-table" style="border-collapse: collapse; margin: 2px 0; font-size: 12px;">
        <thead>
            <tr style="line-height: 1.1;">
                <th style="padding: 1px 3px;"><strong>SI no.</strong></th>
                <th style="padding: 1px 3px;"><strong>Description</strong></th>
                <th style="padding: 1px 3px; text-align: right"><strong>Qty</strong></th>
                <th style="padding: 1px 3px; text-align: right"><strong>Unit Price</strong></th>
                <th style="padding: 1px 3px; text-align: right"><strong>Amount</strong></th>
                <th style="padding: 1px 3px;"><strong>VAT %</strong></th>
                <th style="padding: 1px 3px; text-align: right"><strong>TOTAL</strong></th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->details as $index => $item)
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 3px;">{{ $index + 1 }}</td>
                <td style="padding: 1px 3px;">{{ $item->particular }}</td>
                <td style="padding: 1px 3px; text-align: right">{{ number_format($item->quantity, 0) }}</td>
                <td style="padding: 1px 3px; text-align: right">{{ number_format($item->unit_price, 0) }}</td>
                <td style="padding: 1px 3px; text-align: right">{{ number_format($item->quantity * $item->unit_price, 0) }}</td>
                <td style="padding: 1px 3px;"></td>
                <td style="padding: 1px 3px; text-align: right">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="amount-section" style="margin: 5px 0;">
        <table style="border-collapse: collapse; width: 100%; font-size: 10px;">
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 3px; text-align: right; width: 80%;">TOTAL:</td>
                <td style="padding: 1px 3px; text-align: right;">{{ number_format($invoice->details->sum(function($item) { return $item->quantity * $item->unit_price; }), 2) }}</td>
            </tr>
            @foreach($invoice->taxinvoices as $taxinvoice)
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 3px; text-align: right;">{{ $taxinvoice->tax->tax_name }} ({{ $taxinvoice->tax->rate }}%):</td>
                <td style="padding: 1px 3px; text-align: right;">{{ number_format(($taxinvoice->tax->rate / 100) * $invoice->details->sum(function($item) { return $item->quantity * $item->unit_price; }), 2) }}</td>
            </tr>
            @endforeach
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 3px; text-align: right;">GRAND TOTAL:</td>
                <td style="padding: 1px 3px; text-align: right;">{{ number_format($invoice->totalAmount($invoice->id), 2) }}</td>
            </tr>
            @if($invoice->paidAmount($invoice->id) > 0)
            <tr style="line-height: 1.1;">
                <td style="padding: 1px 3px; text-align: right;">AMOUNT PAID:</td>
                <td style="padding: 1px 3px; text-align: right;">{{ number_format($invoice->paidAmount($invoice->id), 2) }}</td>
