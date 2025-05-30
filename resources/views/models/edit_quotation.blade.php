<div id="edit_quotation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editQuotationModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuotationModalLabel">Edit Quotation</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
            </div>
            <div class="modal-body">
                <form id="editQuotationForm" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row">                  
                        <div class="col-md-12">
                            <div class="form-group mb-8">
                                <label for="customer_id" class="control-label">Select customer</label> <br>
                                <select name="customer_id" id="customer_id" class="form-control">
                                    @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                    </div>
                    
                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="date_quoted" class="control-label">Date</label> <br>
                                <input type="date" name="date_quoted" id="date_quoted" class="form-control">
                            </div>
                        </div>              
                    </div>     
                    
                    <div class="row">                  
                        <div class="col-md-6 mb-16">
                            <div class="form-group">
                                <label for="transaction_currency" class="control-label">Currency</label> <br>
                                <select name="transaction_currency" id="transaction_currency" class="form-control">
                                    @foreach($transaction_currencies as $currency)
                                    <option value="{{ $currency }}">{{ $currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 

                        <div class="col-md-6 mb-16">
                            <div class="form-group">
                                <label for="transaction_type" class="control-label">Transaction type</label> <br>
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    @foreach($transaction_types as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                  
                    </div>  

                    <div class="row">                  
                        <div class="col-md-6 mb-16">
                            <div class="form-group">
                                <label for="container_air_no" class="control-label">Container/Waybill No</label> <br>
                                <input type="text" name="container_air_no" id="container_air_no" class="form-control">
                            </div>
                        </div>  

                        <div class="col-md-6 mb-16">
                            <div class="form-group">
                                <label for="entry_no" class="control-label">Entry No</label> <br>
                                <input type="text" name="entry_no" id="entry_no" class="form-control">
                            </div>
                        </div>             
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-8">
                            <div class="form-group">
                                <label for="description" class="control-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="row">                  
                        <div class="col-md-4 mb-8">
                            <div class="form-group">
                                <label for="weight" class="control-label">Weight (KG)</label> <br>
                                <input type="number" step="0.001" name="weight" id="weight" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
                            <div class="form-group">
                                <label for="volume" class="control-label">Volume (M³)</label> <br>
                                <input type="number" step="0.001" name="volume" id="volume" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 mb-8">
                            <div class="form-group">
                                <label for="packages" class="control-label">Packages</label> <br>
                                <input type="number" name="packages" id="packages" class="form-control">
                            </div>
                        </div>              
                    </div>

                    <button class="btn btn-danger mt-8" type="submit">
                        Update Quotation
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
