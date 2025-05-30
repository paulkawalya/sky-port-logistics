@extends('layouts.app')

@section('content')
<h2>{{ $title }}</h2>
<hr>
<div class="card">
    <div class="card-body">
        <form action="{{ route('invoices.store') }}" method="post">
            @csrf
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="shippment_id" class="control-label">Select Shipment</label>
                        <select name="shippment_id" id="shippment_id" class="form-control">
                            @foreach($shipments as $shipment)
                            <option value="{{ $shipment->id }}">
                                {{ "#" . $shipment->id . " " . $shipment->customer->name . " | Container: " . $shipment->transit->number }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>                
            </div>
            <br/>

            <div class="row mb-4">                  
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="date_invoiced" class="control-label">Invoice Date</label> <br>
                        <input type="date" name="date_invoiced" value="{{ date('Y-m-d') }}" class="form-control">
                    </div>
                </div>              
            </div>  
            <br/>   

            <div class="row">                  
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="transaction_currency" class="control-label">Transaction Currency</label>
                        <select name="transaction_currency" id="transaction_currency" class="form-control">
                            <option value="UGX">UGX</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>
                </div>              
            </div> 

            <hr>
            <button class="btn btn-danger" type="submit">Save</button>
        </form>                  
    </div>        
</div> 
 
@endsection

@push('scripts')
<script>
    $("#shippment_id").chosen({
        width: "100%"
    });
</script>
@endpush
