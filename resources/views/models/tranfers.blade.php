
<div id="make_transfer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Record Tranfer</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">
                   
                </button>
            </div>
            <div class="modal-body p-8">

                <form action="{{route('transfers.store')}}" method="post">
                    @csrf

                    <input type="hidden" name="shippment_id" value="{{$shippment->id}}">           
                   

                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="date_transfered" class="control-label">Date Transfered</label> <br>
                                <input type="date" name="date_transfered" value="{{ date('Y-m-d') }}" class="form-control">
                            </div>
                        </div>              
                    </div>

                    
                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="product_name" class="control-label">Product name</label> <br>
                                <input type="text" name="product_name" value="{{ $shippment->description }}" class="form-control">
                            </div>
                        </div>              
                    </div>
                    
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="quantity" class="control-label">Quantity</label> <br>
                                <input type="number" name="quantity"  class="form-control">
                            </div>
                        </div>              
                    </div>   

                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="destination" class="control-label">Destination</label> <br>
                                <input type="text" name="destination" class="form-control">
                            </div>
                        </div>              
                    </div>

                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="narrative" class="control-label">Narrative</label> <br>
                                <input type="text" name="narrative" class="form-control">
                            </div>
                        </div>              
                    </div>


                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="reference" class="control-label">Reference</label> <br>
                                <input type="text" name="reference" class="form-control">
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