@php
    $shippment = $shippment ?? $shipment ?? null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Pass #{{ $shippment->gate_pass_number ?? ('GP-' . $shippment->id) }} - SIFCARGO TONIGHT</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #0f172a;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }

        .container {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 36px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 24px;
            border-bottom: 2px solid #ed1c24;
            margin-bottom: 24px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-section img {
            height: 60px;
            object-fit: contain;
        }

        .company-info h2 {
            font-size: 20px;
            font-weight: 800;
            color: #ed1c24;
            letter-spacing: -0.5px;
        }

        .company-info p {
            color: #64748b;
            font-size: 12px;
        }

        .pass-meta {
            text-align: right;
        }

        .pass-badge {
            display: inline-block;
            background: #ed1c24;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .pass-date {
            color: #475569;
            font-size: 12px;
        }

        .doc-title {
            text-align: center;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .section-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 12.5px;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            color: #0f172a;
            font-weight: 600;
            text-align: right;
        }

        table.cargo-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        table.cargo-table th {
            background: #1e40af;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: left;
        }

        table.cargo-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12.5px;
        }

        table.cargo-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .notes-box {
            background: #fdfdfd;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 12px;
            color: #334155;
        }

        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .signature-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px;
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .signature-title {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
        }

        .signature-line {
            border-top: 1px dashed #94a3b8;
            margin-top: 40px;
            padding-top: 4px;
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }

        .print-actions {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-print {
            background: #1e40af;
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(30, 64, 175, 0.3);
            transition: all 0.2s ease;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }

            .container {
                border: none;
                box-shadow: none;
                padding: 16px;
                max-width: 100%;
            }

            .print-actions {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>

    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print Gate Pass / Delivery Order
        </button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('app-assets/img/logo/sifcargo.png') }}" alt="SIFCARGO TONIGHT">
                <div class="company-info">
                    <h2>SIFCARGO TONIGHT</h2>
                    <p>Forwarding &amp; Clearing - Sea &amp; Air Cargo Terminal</p>
                    <p>Kampala Bonded Store &amp; Freight Distribution Warehouse</p>
                    <p>Phone: +256 700 000 000 | Email: info@sifcargo.com</p>
                </div>
            </div>
            <div class="pass-meta">
                <div class="pass-badge">
                    {{ $shippment->gate_pass_number ?? ('GP-' . date('Y') . '-' . str_pad($shippment->id, 4, '0', STR_PAD_LEFT)) }}
                </div>
                <div class="pass-date">
                    <strong>Checkout Time:</strong><br>
                    {{ $shippment->dispatched_at ? $shippment->dispatched_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}
                </div>
            </div>
        </div>

        <div class="doc-title">
            OFFICIAL CARGO RELEASE ORDER & SECURITY GATE PASS
        </div>

        <!-- 2 Column Overview -->
        <div class="grid-2">
            <!-- Consignee Information -->
            <div class="section-card">
                <div class="section-title">Consignee & Freight Details</div>
                <div class="info-row">
                    <span class="info-label">Customer / Owner:</span>
                    <span class="info-value">{{ $shippment->customer?->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Contact Phone:</span>
                    <span class="info-value">{{ $shippment->customer?->phone_number ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Shipping Mark:</span>
                    <span class="info-value">{{ $shippment->mark ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Transit / Container #:</span>
                    <span class="info-value">{{ $shippment->transit?->number ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Route:</span>
                    <span class="info-value">{{ $shippment->transit?->departure_place ?? 'Origin' }} &rarr; {{ $shippment->transit?->destination_place ?? 'Kampala' }}</span>
                </div>
            </div>

            <!-- Authorized Receiver Information -->
            <div class="section-card">
                <div class="section-title">Authorized Receiver & Vehicle</div>
                <div class="info-row">
                    <span class="info-label">Receiver Name:</span>
                    <span class="info-value">{{ $shippment->receiver_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Receiver Phone:</span>
                    <span class="info-value">{{ $shippment->receiver_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">National ID / Passport:</span>
                    <span class="info-value">{{ $shippment->receiver_id_number ?? 'Verified at Gate' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Vehicle Registration Plate:</span>
                    <span class="info-value" style="font-size: 13px; color: #1e40af;">{{ $shippment->receiver_vehicle_no ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Warehouse Shelf / Bay:</span>
                    <span class="info-value">{{ $shippment->warehouse_location ?? 'Kampala Terminal' }}</span>
                </div>
            </div>
        </div>

        <!-- Cargo Manifest Table -->
        <table class="cargo-table">
            <thead>
                <tr>
                    <th style="width: 10%;">Item #</th>
                    <th style="width: 40%;">Cargo Description & Specification</th>
                    <th style="width: 15%; text-align: center;">Packages</th>
                    <th style="width: 15%; text-align: center;">Weight (KG)</th>
                    <th style="width: 20%; text-align: center;">Volume (CBM)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#{{ str_pad($shippment->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <strong>{{ $shippment->description ?? 'General Cargo Merchandise' }}</strong><br>
                        <span style="color: #64748b; font-size: 11px;">Shipper: {{ $shippment->shipper ?? 'Consolidated Air/Sea Freight' }} | Type: {{ $shippment->type ?? 'Freight' }}</span>
                    </td>
                    <td style="text-align: center; font-weight: 700;">
                        {{ $shippment->number_of_packages }} {{ $shippment->unit_of_measure ?? 'PCS' }}
                    </td>
                    <td style="text-align: center; font-weight: 700;">
                        {{ $shippment->weight ? number_format($shippment->weight) . ' KG' : 'N/A' }}
                    </td>
                    <td style="text-align: center; font-weight: 700;">
                        {{ $shippment->cbm ? $shippment->cbm . ' m³' : ($shippment->volume ?? 'N/A') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Storage & Condition Notes -->
        <div class="notes-box">
            <strong>Warehouse Condition & Checkout Log:</strong><br>
            Intake Date: {{ $shippment->received_in_store_at ? $shippment->received_in_store_at->format('d M Y') : 'Pre-cleared' }} | 
            Storage Duration: {{ $shippment->days_in_store ?? 0 }} day(s) | 
            Status: <strong>{{ $shippment->cargo_status }}</strong><br>
            Remarks: {{ $shippment->checkout_notes ?? 'Packages examined and handed over in sound condition without shortage or physical damage.' }}
        </div>

        <!-- 3-Way Signatures & Gate Stamp Box -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-title">1. Dispatched by Storekeeper</div>
                <div style="font-size: 11px; color: #1e40af; font-weight: 600;">
                    {{ $shippment->checkedOutBy?->name ?? (auth()->user()?->name ?? 'Storekeeper') }}
                </div>
                <div class="signature-line">Signature & Date</div>
            </div>

            <div class="signature-box">
                <div class="signature-title">2. Consignee / Receiver</div>
                <div style="font-size: 11px; color: #0f172a; font-weight: 600;">
                    {{ $shippment->receiver_name ?? 'Consignee Representative' }}
                </div>
                <div class="signature-line">Signature & Date</div>
            </div>

            <div class="signature-box">
                <div class="signature-title">3. Terminal Security Gate Exit</div>
                <div style="font-size: 10px; color: #64748b; font-style: italic;">
                    Gate Officer Verification Stamp
                </div>
                <div class="signature-line">Security Stamp & Time</div>
            </div>
        </div>
    </div>

</body>
</html>
