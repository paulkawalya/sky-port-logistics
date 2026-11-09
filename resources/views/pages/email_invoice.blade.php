<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVOICE</title>
</head>
<body>

    <img width="35%" src="{{asset('/app-assets/img/logo/sifcargo.png')}}" alt="Sky Port Logistics LTD Logo" class="rounded float-end"> <br>                         
    @include('pages.address')

    <center> <h3><b>INVOICE</b></h3></center>

    <h5><b>CONSIGNEE</b></h5>               
    <span class="bg-light">{{$invoice->shippment->customer->name}}<br>
    {{$invoice->shippment->customer->phone_number}} <br> </span>  
    

    <table>
        <tbody>
        <tr>
            <td class=""><b>Invoice No.</b></td> <td>{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</td>

        </tr>

        <tr>
            <td class=""><b>Date: </b></td> <td>{{Carbon\Carbon::parse($invoice->created_at )->format("d M Y")}}</td>
        </tr>

        <tr >
            <td class=""><b>Currency: </b></td> <td>{{$invoice->transaction_currency}}</td>
        </tr>
        </tbody>
    </table> 


    <table class="">
        <tbody>
        <tr>
            <td class=""><b>Container No.</b></td> <td> {{ $invoice->shippment->transit->number }} </td>
        </tr>

        <tr>
            <td class=""><b>Volume: </b></td> <td> {{ $invoice->shippment->volume." ".$invoice->shippment->unit_of_measure }} </td>
        </tr>

        <tr>
            <td class=""><b>Packages: </b></td> <td> {{ $invoice->shippment->bl_packages }} </td>
        </tr>
        </tbody>
    </table> 

    <table class="table table-sm table-bordered table-striped">
        <thead>
            <th>NO.</th>  <th>Description</th> <th>Quantity</th> <th>Unit Price</th> <th>Amount</th>                  
        </thead>

        <tbody>
            @foreach($invoice->details as $row => $detail)
                <tr>
                    <td>{{$row+1}}</td>
                    <td>{{ $detail->particular }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->unit_price }}</td>
                    <td>{{ number_format($detail->quantity * $detail->unit_price) }}</td>                          
                </tr>
            @endforeach                    
            </tbody>
    </table>
</body>
</html>