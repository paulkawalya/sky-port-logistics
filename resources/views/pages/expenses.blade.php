@extends('layouts.app')

@section('content')
<h2>{{ $title }}</h2>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-hover table-striped" id="tabledata">
                <thead>
                 <th>#</th> <th>Requisition</th>  <th>Item</th> <th>Quantity</th>  <th>Unit price</th> <th>Amount</th> <th>Reason</th> <th>By</th>
                </thead>

                <tbody>
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{$expense->id}}</td>
                        <td>
                            <a href="{{ route('requisitions.show',$expense->requisition_id) }}"> {{$expense->requisition_id}}</a>
                        </td>
                        <td>{{ $expense->particular }}</td>
                        <td>{{ $expense->quantity }}</td>
                        <td>{{ $expense->estimated_cost }}</td>                                
                        <td>{{ number_format($expense->quantity * $expense->estimated_cost) }}</td>
                        <td>{{ $expense->reason }}</td>
                        <td>{{ $expense->user->name }}</td>
                    </tr>
                @endforeach                    
                </tbody>
            </table>
                       
        </div>        
    </div>
</div>     
@endsection

@include('layouts.data_tables')