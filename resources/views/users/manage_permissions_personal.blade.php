@extends('layouts.app')
@section('content')
<div class="container">
    <h4 class="page-title">{{$title}}</h4>
    <div class="card">
        <div class="card-body">
            <p id="display_message" class="text-danger"></p>
            <div class="row">
                @foreach($readModelComponents as $readModelComponent)
                    <div class="col-xl-6 col-md-3 col-lg-3 col-sm-6 col-xs-6">
                        <div class='model_component'>

                            <h4>{{ $readModelComponent->name }}</h4>
                            <hr>
                            @foreach ( $readModelComponent->permissions as  $permission )

                              <div>
                                  @if(App\Models\UserPermission::checkUserPermision($user->id,$permission->id))

                                        <input type="checkbox" checked disabled value="{{ $permission->id }}" id="permission_{{ $permission->id }}"/> {{ $permission->display_name }}

                                    @else

                                        <input type="checkbox" disabled value="{{ $permission->id }}" id="permission_{{ $permission->id }}"/> {{ $permission->display_name }}

                                  @endif
                              </div>                         
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
          </div>
        </div>
    </div>
@endsection
 