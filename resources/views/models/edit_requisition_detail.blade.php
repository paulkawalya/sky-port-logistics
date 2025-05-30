
<div id="edit_requisition_item{{$detail->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Item</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
            </div>
            <div class="modal-body p-8">

                <form action="{{route('requisition_details.update',$detail->id)}}" method="post">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="requisition_id" value="{{$requisition->id}}"> 

                    <label for="expense_category_id">Expense category</label>
                    <select name="expense_category_id" class="form-control"> 
                        <option value=""></option>                   
                        @foreach($expense_categories as $expense_category)
                            <option value="{{ $expense_category->id }}" {{ $detail->expense_category_id == $expense_category->id ?'selected':'' }} >{{ $expense_category->name }}</option>
                        @endforeach
                    </select>
                   

                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="particular" class="control-label">Particular</label> <br>
                                <input type="text" name="particular" value="{{ $detail->particular }}" class="form-control">
                            </div>
                        </div>              
                    </div>

                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="reason" class="control-label">Justify</label> <br>
                                <input type="text" name="reason" value="{{ $detail->reason }}" class="form-control">
                            </div>
                        </div>              
                    </div>                   

                    
                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="quantity" class="control-label">Quantity</label> <br>
                                <input type="number" name="quantity" value="{{ $detail->quantity }}" class="form-control">
                            </div>
                        </div>              
                    </div>

                    
                    <div class="row">                  
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="estimated_cost" class="control-label">Estimated cost</label> <br>
                                <input type="number" name="estimated_cost" value="{{ $detail->estimated_cost }}" class="form-control">
                            </div>
                        </div>              
                    </div>

                    <hr>
                    <button class="btn btn-danger" type="submit">Save changes</button>
                </form>

            </div>
        </div>
    </div>
</div>