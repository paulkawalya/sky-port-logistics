
<div id="add_new_invoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Invoice</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">
                   
                </button>
            </div>
            <div class="modal-body p-8">

                <form action="{{route('invoices.store')}}" method="post">
                    @csrf

                    <input type="hidden" name="shippment_id" value="{{$shippment->id}}">        
                   
                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="date_invoiced" class="control-label">Date invoiced</label> <br>
                                <input type="date" name="date_invoiced" value="{{ date('Y-m-d') }}"  class="form-control">
                            </div>
                        </div>              
                    </div>     
                    
                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="transaction_currency" class="control-label">Transaction Currency</label>
                                <select name="transaction_currency" id="transaction_currency" class="form-control">
                                    <option value=""></option>
                                    <option value="USD">USD</option>
                                    <option value="UGX">UGX</option>
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