@extends('layouts.print_master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            @include('pages.address')
        </div>
        <div class="col-md-6 text-end">
            <img width="35%" src="{{asset('/app-assets/img/logo/sifcargo.png')}}" alt="SIFCARGO TONIGHT Logo" class="float-end">
        </div>
    </div>

    <div class="text-center mt-4">
        <h3 class="text-uppercase"><strong>INVOICE</strong></h3>
    </div>
    
    <hr style="height:2px; border:none; color:#dc0000; background-color:#dc0000;">

    <div class="row mt-4">
        <div class="col-md-4">
            <h5><strong>CONSIGNEE</strong></h5>
            <p class="bg-light">
                {{$invoice->shippment->customer->name}}<br>
                {{$invoice->shippment->customer->phone_number}}
            </p>
        </div>

        <div class="col-md-4">
            <table class="table table-sm table-borderless">
                <tr>
                    <td><strong>Invoice No.</strong></td>
                    <td>{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>{{Carbon\Carbon::parse($invoice->created_at)->format("d M Y")}}</td>
                </tr>
                <tr>
                    <td><strong>Currency:</strong></td>
                    <td>{{$invoice->transaction_currency}}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-4">
            <table class="table table-sm table-borderless">
                <tr>
                    <td><strong>Container No.</strong></td>
                    <td>{{ $invoice->shippment->transit->number }}</td>
                </tr>
                <tr>
                    <td><strong>Volume:</strong></td>
                    <td>{{ $invoice->shippment->volume." ".$invoice->shippment->unit_of_measure }}</td>
                </tr>
                <tr>
                    <td><strong>Packages:</strong></td>
                    <td>{{ $invoice->shippment->bl_packages }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->details as $row => $detail)
                            <tr>
                                <td>{{$row+1}}</td>
                                <td>{{ $detail->particular }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ number_format($detail->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format($detail->quantity * $detail->unit_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6 offset-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-end"><strong>Sub Total:</strong></td>
                            <td class="text-end">{{ number_format($invoice->amount($invoice->id), 2) }}</td>
                        </tr>
                        @foreach($invoice->taxinvoices as $taxinvoice)
                        <tr>
                            <td class="text-end"><strong>{{$taxinvoice->tax->tax_name}}:</strong></td>
                            <td class="text-end">{{ number_format($taxinvoice->amount($taxinvoice->id), 2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-end"><strong>Total:</strong></td>
                            <td class="text-end">{{ number_format($invoice->totalAmount($invoice->id), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-end"><strong>Paid:</strong></td>
                            <td class="text-end">{{ number_format($invoice->paidAmount($invoice->id), 2) }}</td>
                        </tr>
                        <tr class="table-active">
                            <td class="text-end"><strong>Balance:</strong></td>
                            <td class="text-end">{{ number_format($invoice->balance($invoice->id), 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4 text-center">
            <div style="border-top: 1px solid #000; padding-top: 10px;">
                Prepared by: {{ $invoice->user->name }}
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div style="border-top: 1px solid #000; padding-top: 10px;">
                Approved by
            </div>
        </div>
        <div class="col-md-4 text-center">
            <div style="border-top: 1px solid #000; padding-top: 10px;">
                Received by
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    body {
        font-size: 12px;
        line-height: 1.3;
        background: white !important;
    }
    .table {
        color: #000;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #000 !important;
    }
    .table th {
        background-color: #fff !important;
    }
    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .table {
            width: 100% !important;
        }
        .table th {
            background-color: #fff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection
@endsection
