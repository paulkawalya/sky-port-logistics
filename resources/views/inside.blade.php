@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h2>Hello {{Auth::user()->name}}!</h2>
        <p>Welcome back.</p>        
    </div>
</div>     
@endsection
