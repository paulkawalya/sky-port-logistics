@extends('layouts.print_master')

@section('content')
  <div class="card-box" style="color: #000">
      <div class="card-body">
          <div class="row"> 
              <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                @include('pages.address')
              </div>              

              <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
                <center>
                  <!-- <img width="50%" src="{{asset('/app-assets/img/logo/sifcargo.png')}}" alt="SKY PORT LOGISTICS LTD"> -->
                  <img width="35%" src="{{asset('/app-assets/img/logo/sifcargo.png')}}" alt="SKY PORT LOGISTICS LTD" class="rounded float-end">
                </center>   
              </div>
          </div>    

          <hr style="height:3px; border:none;color:red;background-color:red;">

          <div class="row"> 
              <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                 <span class="text-danger"><strong>From:</strong></span> <br>
                <span>{{ $requisition->user->name }}</span> <br>
                <span>{{ $requisition->user->phone_number }}</span> <br>
                <span>{{ $requisition->user->email }}</span>  <br>
              </div>

              <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">
                <center>
                        <h1><u>REQUISITION</u></h1>
                </center>         
                </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-4">

               <strong>
                    <span class="text-danger">No. {{$requisition->id}}</span><br>
                    <span>{{Carbon\Carbon::parse($requisition->date_requisitioned )->format("d M Y")}}</span>                 
                </strong>

            </div>
          </div>
          
          <hr>

          <div class="row">        
              <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                  <table class="table table-hover table-striped">
                      <thead>
                        <th>#</th>  <th>Item</th> <th>Quantity</th>  <th>Unit price</th> <th>Amount</th> <th>Reason</th>                       
                       </thead>

                      <tbody>
                        @foreach($requisition->details as $row => $detail)
                            <tr>
                                <td>{{$row+1}}</td>
                                <td>{{ $detail->particular }} <br>{{  empty($detail->expense_category_id) ? "": $detail->category->name }}</td>
                                <td>{{ $detail->quantity }}</td>
                                <td>{{ $detail->estimated_cost }}</td>                                
                                <td>{{ number_format($detail->quantity * $detail->estimated_cost) }}</td>
                                <td>{{ $detail->reason }}</td>                               
                            </tr>
                        @endforeach                    
                       </tbody>
                  </table>

                  <hr>               
               
                  <div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6">

                    <h4>Approvals</h4>
                    <table class="table table-hover table-striped">
                        <tbody>
                            @foreach($requisition->approvals as $approval)
                                <tr>
                                    <td>{{ $approval->user->name }}</td> <td>{{ $approval->status }}</td> <td>{{ $approval->remarks }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                    

                    @if($payments->count() > 0)
                    <h4>Payouts</h4>
                      <table class="table table-sm table-striped table-light">
                        <thead>
                            <th>Date</th> <th>Amount</th> <th>Mode</th><th>By</th>
                        </thead>

                        <tbody>
                            
                            @foreach($payments as $payment)                    
                            <tr>
                              <td>{{ $payment->payment_date }}</td>
                              <td>{{ number_format($payment->amount)  }}</td>
                              <td>{{ $payment->mode_of_payment }}</td>
                              <td>{{ $payment->user->name }}</td>                          
                            </tr>
                            @endforeach 
                        </tbody>
                      </table>
                      @endif

                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>Amount: </td> <td>{{  number_format($requisition->amount($requisition->id)) }}</td>
                                </tr> 
                            </tbody>
                        </table>   
                        
                        <h4>Documents</h4>

                        @foreach($documents as $document)
                        {{ $document->created_at }}  <a href="{{ asset('documents/'.$document->file_url) }}">{{ $document->file_name }}</a> <br>
                        @endforeach
                    </div>
                  </div>              

                  <hr>              
                  <p>Printed by:  {{Auth::user()->name}}</p>
                  <p>Printing time: {{now()}}</p>
                  <p>Thank you.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
