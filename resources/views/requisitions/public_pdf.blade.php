<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition #{{ str_pad($requisition->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        @page {
            margin: 1cm;
            size: 210mm 297mm; /* A4 exact dimensions */
            marks: none;
            margin-header: 0;
            margin-footer: 0;
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
            margin: 0 auto;
            letter-spacing: 0.1px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
        }
        .header img {
            width: auto;
            max-width: 150px;
            max-height: 60px;
            object-fit: contain;
            margin: 5px auto;
            display: block;
        }
        .company-info {
            text-align: left;
            font-size: 10px;
            line-height: 1.3;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .requisition-title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            margin: 10px 0;
            color: #dc0000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .red-line {
            height: 2px;
            background-color: #dc0000;
            border: none;
            margin: 5px 0 15px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        .details-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .details-table th:last-child, .details-table td:last-child,
        .details-table th:nth-last-child(2), .details-table td:nth-last-child(2) {
            text-align: right;
        }
        .signature-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .signature-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .approvals-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .approvals-table th, .approvals-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        .approvals-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin: 15px 0 5px;
            color: #333;
        }
        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(220, 0, 0, 0.1);
            z-index: -1;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="watermark">SIFCARGO TONIGHT</div>
    
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/app-assets/img/logo/sifcargo_icon.png'))) }}" alt="SIFCARGO TONIGHT" style="width: auto; max-width: 550px; max-height: 130px; margin: 0 auto 5px; display: block;">
    </div>

    <hr class="red-line">

    <table style="width: 100%; max-width: 190mm; border-collapse: collapse; margin-bottom: 15px; table-layout: fixed;">
        <tr>
            <td style="width: 33%; vertical-align: top; padding-right: 5px;">
                <div style="font-size: 11px;">
                    <h5 style="font-weight: bold; margin: 0 0 2px 0; font-size: 12px; color: #dc0000;">FROM:</h5>
                    <div style="margin-bottom: 1px;">{{ $requisition->user->name }}</div>
                    <div style="margin-bottom: 1px;">{{ $requisition->user->phone_number }}</div>
                    <div>{{ $requisition->user->email }}</div>
                </div>
            </td>
            
            <td style="width: 33%; vertical-align: top; text-align: center;">
                <h1 style="font-size: 18px; text-decoration: underline; margin: 0;">REQUISITION</h1>
            </td>
            
            <td style="width: 33%; vertical-align: top; text-align: right;">
                <div style="font-size: 11px; font-weight: bold;">
                    <div style="margin-bottom: 5px; color: #dc0000;">No. {{ str_pad($requisition->id, 4, '0', STR_PAD_LEFT) }}</div>
                    <div>{{ \Carbon\Carbon::parse($requisition->date_requisitioned)->format('d M Y') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <hr style="border: none; border-top: 1px solid #ddd; margin: 5px 0;">

    <table class="details-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Item</th>
                <th style="width: 10%;">Quantity</th>
                <th style="width: 15%;">Unit Price</th>
                <th style="width: 15%;">Amount</th>
                <th style="width: 25%;">Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisition->details as $row => $detail)
            <tr>
                <td>{{ $row+1 }}</td>
                <td>
                    {{ $detail->particular }}
                    @if(!empty($detail->expense_category_id))
                    <br><small>{{ $detail->category->name }}</small>
                    @endif
                </td>
                <td>{{ $detail->quantity }}</td>
                <td style="text-align: right;">{{ number_format($detail->estimated_cost, 0) }}</td>
                <td style="text-align: right;">{{ number_format($detail->quantity * $detail->estimated_cost, 0) }}</td>
                <td>{{ $detail->reason }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: flex; margin-top: 20px;">
        <div style="width: 50%; padding-right: 10px;">
            <div class="section-title">APPROVALS</div>
            <table class="approvals-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requisition->approvals as $approval)
                    <tr>
                        <td>{{ $approval->user->name }}</td>
                        <td>{{ $approval->status }}</td>
                        <td>{{ $approval->remarks }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($payments) > 0)
            <div class="section-title">PAYOUTS</div>
            <table class="approvals-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td style="text-align: right;">{{ number_format($payment->amount, 0) }}</td>
                        <td>{{ $payment->mode_of_payment }}</td>
                        <td>{{ $payment->user->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

        <div style="width: 50%;">
            <div style="margin-bottom: 20px;">
                <div style="margin-bottom: 10px; font-size: 11px;">
                    <b>AMOUNT IN WORDS: </b>
                    <span style="text-transform: uppercase; color: #dc0000; font-weight: bold;">{{ \App\Models\Invoice::numberToWords(round($requisition->amount($requisition->id))) }} {{ $requisition->currency ?? 'UGX' }} ONLY</span>
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 5px; font-weight: bold; width: 40%;">Amount:</td>
                        <td style="padding: 5px; text-align: right; width: 60%;">{{ number_format($requisition->amount($requisition->id), 0) }}</td>
                    </tr>
                </table>
            </div>

            @if(count($documents) > 0)
            <div class="section-title">DOCUMENTS</div>
            <table class="approvals-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>File Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->created_at->format('d M Y') }}</td>
                        <td>{{ $document->file_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    <div class="footer">
        <div>This is a secure document generated on: {{ $current_date }}</div>
        <div>This document is for reference only and does not require a signature.</div>
        <div style="color: #dc0000; font-weight: bold;">SIFCARGO - TONIGHT</div>
    </div>
</body>
</html>
