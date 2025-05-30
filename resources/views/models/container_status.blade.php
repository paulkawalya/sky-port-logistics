
<div id="update_status" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Container Status</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
            </div>
            <div class="modal-body p-8">

                <form action="{{route('container_status.store')}}" method="post">
                    @csrf

                    <input type="hidden" name="transit_id" value="{{$transit->id}}">
            
                    <div class="row">
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="filename" class="control-label">Staus</label>
                                <select name="status" id="" class="form-control">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                             </div>
                        </div>
                        
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="narrative" class="control-label">Narrative</label>
                                <input type="text" class="form-control" name="narrative">
                            </div>
                        </div>              
                    </div>

                    <hr>
                    <button class="btn btn-danger" type="submit">Save</button>
                </form>

            </div>
        </div>
    </div>
</div>