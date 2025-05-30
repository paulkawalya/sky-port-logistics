@extends('layouts.print_master')
  @section('content')
    <!-- <a href="#" id="hide_form"  class="btn btn-outline-danger btn-rounded btn-sm">Print {{ $quotation->transaction_type }}</a>  -->
 
<div class="card-box" style="color: #000">
  <div class="card-body">
      <div class="row"> 
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
              @include('pages.address')
          </div>              

          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <img width="75%" src="{{asset('/app-assets/img/logo/tonightgroup.jpeg')}}" alt="" class="rounded float-end">                         
          </div>
      </div>    

      <center>
        <h3 style="text-transform: uppercase;"><b>{{ $quotation->transaction_type }}</b></h3>
      </center> 
      <hr style="height:3px; border:none;color:red;background-color:red;">

      <div class="row"> 
          <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
              <h5><b>CONSIGNEE</b></h5>               
              <span class="bg-light">{{$quotation->customer->name}}<br>
              {{$quotation->customer->phone_number}} <br> </span>            
          </div>

          <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
            <table class="table table-sm table-borderless">
              <tbody>
                <tr>
                  <td class=""><b>Entry No.</b></td> <td>{{$quotation->entry_no}}</td>

                </tr>

                <tr>
                    <td class=""><b>Container / Way Bill No </b></td> <td>{{$quotation->container_air_no}}</td>
                </tr>

                
                
              </tbody>
            </table> 

          </div>

          <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
            <table class="table table-sm table-borderless">
              <tbody>
                <tr>
                  <td class=""><b>No.</b></td> <td>{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</td>

                </tr>

                <tr>
                    <td class=""><b>Date: </b></td> <td>{{Carbon\Carbon::parse($quotation->created_at )->format("d M Y")}}</td>
                </tr>

                <tr >
                    <td class=""><b>Currency: </b></td> <td>{{$quotation->transaction_currency}}</td>
                </tr>
              </tbody>
            </table> 

          </div>
          
      </div>
      
      <hr>

      <div class="row">        
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
          <div class="table-responsive">
            <table class="table table-sm table-bordered table-striped" style="border: 2px solid black;">
                <thead>
                  <th>NO.</th>  <th>Description</th> <th>Quantity</th> <th>Unit Price</th> <th>Amount</th>
                </thead>

                <tbody>
                  @foreach($quotation->details as $row => $detail)
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
          </div>
            <hr>                
        
            <div class="row">
              <div class="col-md-6 col-sm-6 col-lg-6">
                <b>AMOUNT IN WORDS: </b><br/>
                <span class="text-uppercase text-danger"></span>
               
                @if($quotation->payments->count() > 0)
                <table class="table table-sm table-striped table-light">
                  <thead>
                      <th>Date</th> <th>Amount</th> <th>Mode</th>
                  </thead>

                  <tbody>
                      
                      @foreach($quotation->payments as $payment)                    
                      <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ number_format($payment->amount_paid)  }}</td>
                        <td>{{ $payment->mode_of_payment }}</td>
                      </tr>
                      @endforeach 
                  </tbody>
                </table>
                @endif
             </div>

             <div class="col-md-6 col-sm-6 col-lg-6">
                  <table class="table table-sm table-striped table-light">
                      <tbody>
                          <tr>
                              <td class=""><b>Amount: </b></td> <td>{{  number_format($quotation->amount($quotation->id),2) }}</td>
                          </tr>

                          @foreach($quotation->taxquotations as $taxquotation)

                            <tr>
                             
                                <td class=""><b>{{$taxquotation->tax->tax_name}}: </b></td> 
                                <td>
                                  {{ number_format($taxquotation->amount($taxquotation->id)) }}
                                 </td>
                           
                            </tr>

                          @endforeach

                          <tr>
                            <td class=""><b>TOTAL AMOUNT: </b></td> <td>{{ number_format($quotation->totalAmount($quotation->id)) }}</td>
                          </tr> 

                          @if($quotation->transaction_type=="invoice")

                          <tr>
                              <td class=""><b>Paid: </b></td> <td>{{  number_format($quotation->paid($quotation->id),2) }}</td>
                          </tr>

                          <tr>
                              <td class=""><b>Balance: </b></td> <td>{{  number_format($quotation->balance($quotation->id),2) }}</td>
                          </tr>
                          @endif
                      </tbody>
                  </table>                        
              </div>
            </div>
        

            <hr>  
            <table class="table table-sm table-striped table-borderless ">
                <tbody>
                    <tr>
                        <td class=""><b>Processed by: </b></td>
                    </tr>

                    <tr>
                        <td><span class="badge p-8">{{$quotation->user->name}} <br> {{ now() }} </span></td>                        
                    </tr>               
                </tbody>
            </table>

            <            
            
          </div>
        </div>
      </div>
  </div>
</div>
@endsection 
 