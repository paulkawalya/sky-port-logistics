<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($quotation->transaction_type) }} #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        @page {
            margin: 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: 'Roboto', sans-serif, Arial, Helvetica;
            margin: 0;
            padding: 0;
            font-size: 30px;
            line-height: 1.6;
            color: #333;
            background-color: #ffffff;
        }
        .document-container {
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            border-bottom: 3px solid #dc0000;
            padding-bottom: 15px;
        }
        .header img {
            max-width: 800px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .document-title-container {
            background-color: #dc0000;
            color: white;
            text-align: center;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .document-title {
            font-size: 40px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
        }
        .document-number {
            font-size: 30px;
            margin-top: 5px;
        }
        .info-section {
            display: flex;
            margin-bottom: 25px;
        }
        .info-box {
            flex: 1;
            padding: 15px;
        }
        .info-box.client {
            background-color: #f9f9f9;
            border-left: 4px solid #dc0000;
            border-radius: 4px;
        }
        .info-box.details {
            text-align: right;
        }
        .info-title {
            font-weight: bold;
            color: #dc0000;
            margin-bottom: 8px;
            font-size: 30px;
            text-transform: uppercase;
        }
        .info-content {
            line-height: 1.6;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .details-table th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #dc0000;
            font-size: 30px;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        .details-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .details-table th:last-child,
        .details-table td:last-child,
        .details-table th:nth-last-child(2),
        .details-table td:nth-last-child(2) {
            text-align: right;
        }
        .amount-section {
            margin: 30px 0;
            display: flex;
            justify-content: flex-end;
        }
        .amount-table {
            width: 50%;
            border-collapse: collapse;
        }
        .amount-table td {
            padding: 8px 12px;
        }
        .amount-table tr.subtotal td {
            border-bottom: 1px solid #e0e0e0;
        }
        .amount-table tr.tax td {
            border-bottom: 1px solid #e0e0e0;
            color: #555;
        }
        .amount-table tr.total td {
            font-weight: bold;
            font-size: 30px;
            color: #dc0000;
            border-top: 2px solid #dc0000;
            border-bottom: 2px solid #dc0000;
        }
        .amount-table tr.paid td,
        .amount-table tr.balance td {
            color: #333;
        }
        .amount-table td:first-child {
            text-align: left;
            font-weight: 600;
        }
        .amount-table td:last-child {
            text-align: right;
        }
        .amount-in-words {
            margin: 25px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 4px solid #dc0000;
            font-style: italic;
            color: #333;
            border-radius: 4px;
        }
        .amount-in-words strong {
            color: #dc0000;
            font-weight: bold;
            text-transform: uppercase;
        }
        .payment-history {
            margin: 30px 0;
        }
        .payment-title {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 2px solid #dc0000;
            padding-bottom: 5px;
            display: inline-block;
        }
        .bank-details {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        .bank-details-title {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .bank-account {
            margin-bottom: 5px;
        }
        .bank-note {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            font-style: italic;
            color: #666;
            font-size: 28px;
            line-height: 1.5;
        }
        .signature-section {
            margin-top: 120px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            flex: 1;
            text-align: center;
            margin: 0 10px;
        }
        .signature-line {
            border-top: 1px solid #dc0000;
            padding-top: 5px;
            font-weight: bold;
            color: #333;
        }
        .signature-name {
            margin-top: 30px;
            font-size: 28px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 26px;
            color: #666;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
        }
        .footer-company {
            color: #dc0000;
            font-weight: bold;
            margin-top: 5px;
            font-size: 30px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(220, 0, 0, 0.05);
            z-index: -1;
            white-space: nowrap;
            font-weight: bold;
        }
        .tin-number {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 30px;
            font-weight: bold;
        }
        .qr-code {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 80px;
            height: 80px;
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="watermark">SIFCARGO TONIGHT</div>

    <div class="document-container">
         <div class="header">
            <div class="tin-number">TIN: 1033561397</div>
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/app-assets/img/logo/sifcargo_icon.png'))) }}" alt="SIFCARGO TONIGHT" style="width: auto; max-width: 850px; max-height: 130px; margin: 0 auto 5px; display: block;">
        </div>


        <div class="document-title-container">
            <div class="document-title">{{ strtoupper($quotation->transaction_type) }}</div>
            <div class="document-number">{{ ($quotation->transaction_type === 'invoice' ? 'INV' : 'QUO') }}{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</div>
        </div>

        <div class="info-section">
            <div class="info-box client">
                <div class="info-title">Client Information</div>
                <div class="info-content">
                    <strong>{{ $quotation->customer->name }}</strong><br>
                    {{ $quotation->customer->address }}<br>
                    @if($quotation->customer->phone)
                    Phone: {{ $quotation->customer->phone }}<br>
                    @endif
                    @if($quotation->customer->email)
                    Email: {{ $quotation->customer->email }}
                    @endif
                </div>
            </div>

            <div class="info-box details">
                <div class="info-title">Document Details</div>
                <div class="info-content">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: left; padding: 3px 0;"><strong>Date:</strong></td>
                            <td style="text-align: right; padding: 3px 0;">{{ \Carbon\Carbon::parse($quotation->date_quoted)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding: 3px 0;"><strong>Currency:</strong></td>
                            <td style="text-align: right; padding: 3px 0;">{{ $quotation->transaction_currency }}</td>
                        </tr>
                        @if($quotation->container_air_no)
                        <tr>
                            <td style="text-align: left; padding: 3px 0;"><strong>Container/Air No:</strong></td>
                            <td style="text-align: right; padding: 3px 0;">{{ $quotation->container_air_no }}</td>
                        </tr>
                        @endif
                        @if($quotation->entry_no)
                        <tr>
                            <td style="text-align: left; padding: 3px 0;"><strong>Entry No:</strong></td>
                            <td style="text-align: right; padding: 3px 0;">{{ $quotation->entry_no }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        @if($quotation->description)
        <div style="margin-bottom: 20px; padding: 10px; background-color: #f9f9f9; border-radius: 4px;">
            <strong>Description:</strong> {{ $quotation->description }}
        </div>
        @endif

        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 45%;">Description</th>
                    <th style="width: 10%;">Qty</th>
                    <th style="width: 15%;">Unit Price</th>
                    <th style="width: 15%;">Amount</th>
                    @if($quotation->taxquotations->count() > 0)
                    <th style="width: 10%;">VAT</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->particular }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($detail->unit_price, 0) }}</td>
                    <td style="text-align: right;">{{ number_format($detail->quantity * $detail->unit_price, 0) }}</td>
                    @if($quotation->taxquotations->count() > 0)
                    <td style="text-align: center;">
                        @if($detail->is_taxable)
                        <span style="font-size: 30px; color: #dc0000;">✓</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="amount-section">
            <table class="amount-table">
                <tr class="subtotal">
                    <td>Subtotal:</td>
                    <td>{{ number_format($quotation->amount($quotation->id), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>

                @foreach($quotation->taxquotations as $tax)
                <tr class="tax">
                    <td>{{ $tax->tax->tax_name }} ({{ $tax->tax->rate }}%):</td>
                    <td>{{ number_format(($tax->tax->rate / 100) * $quotation->amount($quotation->id), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>
                @endforeach

                <tr class="total">
                    <td>TOTAL:</td>
                    <td>{{ number_format($quotation->totalAmount($quotation->id), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>

                @if($quotation->paid($quotation->id) > 0)
                <tr class="paid">
                    <td>Paid:</td>
                    <td>{{ number_format($quotation->paid($quotation->id), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>
                <tr class="balance">
                    <td>Balance:</td>
                    <td>{{ number_format($quotation->balance($quotation->id), 0) }} {{ $quotation->transaction_currency }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="amount-in-words">
            Amount in words: <strong>{{ \App\Models\Invoice::numberToWords(round($quotation->totalAmount($quotation->id))) }} {{ $quotation->transaction_currency }} ONLY</strong>
        </div>

        @if($quotation->payments->count() > 0)
        <div class="payment-history">
            <div class="payment-title">Payment History</div>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Reference</th>
                        <th>By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quotation->payments as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                        <td style="text-align: right;">{{ number_format($payment->amount_paid, 0) }}</td>
                        <td>{{ $payment->mode_of_payment }}</td>
                        <td>{{ $payment->reference }}</td>
                        <td>{{ $payment->user->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <div class="bank-details">
            <div class="bank-details-title">Bank Details</div>
            <div class="bank-account"><strong>ACCOUNT NAME:</strong> GRULAND LOGISTICS LTD</div>
            <div class="bank-account"><strong>BANK:</strong> EQUITY BANK</div>
            <div class="bank-account"><strong>ACCOUNT NUMBER (UGX):</strong> 1002201945777</div>
            <div class="bank-account"><strong>ACCOUNT NUMBER (USD):</strong> 1002201945782</div>
            <div class="bank-note">
                All invoices issued by the Company shall be paid by the Client within 2 (two) days of the date of invoice unless otherwise agreed in writing by the Company. Invoices unpaid for more than 30 days after the due date as per invoice will incur a surcharge of 2% per month of the outstanding amount.
            </div>
        </div>

        <div class="signature-section">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 33.33%; text-align: center; border-top: 2px solid #dc0000; padding-top: 10px; font-weight: bold; font-size: 30px;">Prepared by: {{ $quotation->user->name }}</td>
                    <td style="width: 33.33%; text-align: center; border-top: 2px solid #dc0000; padding-top: 10px; font-weight: bold; font-size: 30px;">Verified by</td>
                    <td style="width: 33.33%; text-align: center; border-top: 2px solid #dc0000; padding-top: 10px; font-weight: bold; font-size: 30px;">Received by</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <div style="font-size: 30px;">This is a secure document generated on: {{ date('d M Y H:i:s') }}</div>
            <div style="font-size: 30px;">This document is for reference only and does not require a signature.</div>
            <div class="footer-company" style="font-size: 30px;">SIFCARGO - TONIGHT</div>
        </div>
    </div>
</body>
</html>
