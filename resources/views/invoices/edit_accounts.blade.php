@extends('layouts.app')

@section('content')
<h2>{{ $title }}</h2>
<hr>
<div class="card">
    <div class="card-body">
        <form action="{{ route('payment_accounts.update',$paymentAccount->id) }}" method="post" class="col-6">
            @csrf
            @method('PATCH')
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="account_type" class="control-label">Account type</label>
                        <select name="account_type" id="account_type" class="form-control">
                            @foreach($account_types as $account_type)
                            <option value="{{ $account_type }}" {{ $paymentAccount->account_type == $account_type ? 'selected':''  }} > {{ $account_type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>                
            </div>
            <br/>

            <div class="row mb-4">                  
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="name" class="control-label">Account Name</label> <br>
                        <input type="text" name="name" class="form-control" value="{{ $paymentAccount->name  }}">
                    </div>
                </div>              
            </div>  

            <div class="row mb-4">                  
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="account_number" class="control-label">Account Number (Optional)</label> <br>
                        <input type="text" name="account_number" class="form-control" value="{{ $paymentAccount->account_number }}">
                    </div>
                </div>              
            </div>            
            <br/>   

            <div class="row">                  
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="transaction_currency" class="control-label">Currency</label>
                        <select name="transaction_currency" id="transaction_currency" class="form-control">
                            @foreach($transaction_currencies as $transaction_cur)
                            <option value="{{ $transaction_cur }}"  {{ $paymentAccount->transaction_currency == $transaction_cur ? 'selected':'' }}>{{ $transaction_cur }}</option>
                            @endforeach                          
                        </select>
                    </div>
                </div>              
            </div> 

            <div class="row mb-4">                  
                <div class="col-md-12">
                    <div class="form-group mb-16">
                        <label for="others" class="control-label">Other information (Optional)</label> <br>
                        <input type="text" name="others" class="form-control" value="{{$paymentAccount->others}}">
                    </div>
                </div>              
            </div>            
            <br/>  

            <hr>
            <button class="btn btn-danger" type="submit">Save changes</button>
        </form>                  
    </div>        
</div> 
 
@endsection

