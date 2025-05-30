
<div id="add_new_requisition" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Requisition</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
            </div>
            <div class="modal-body p-8">

                <form action="{{route('requisitions.store')}}" method="post">
                    @csrf
                   
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="date_requisitioned" class="control-label">Requisition Date</label> <br>
                                <input type="date" name="date_requisitioned" value="{{ date('Y-m-d') }}"  class="form-control">
                            </div>
                        </div>              
                    </div>     
                    
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="currency" class="control-label">Currency</label>
                                <select name="currency" id="currency" class="form-control">
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
    </div>
</div>