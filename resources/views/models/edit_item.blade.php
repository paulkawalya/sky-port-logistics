<div id="edit_item{{$detail->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Item</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
            </div>
            <div class="modal-body p-8">

                @if(isset($quotation))
                <form action="{{route('quotation_details.update',$detail->id)}}" method="post" enctype="multipart/form-data">
                    @else
                    <form action="{{route('invoice_details.update',$detail->id)}}" method="post" enctype="multipart/form-data">
                        @endif

                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-12 mb-6">
                                <div class="form-group">
                                    <label for="particular" class="control-label">Particular</label> <br>
                                    <input type="text" name="particular" value="{{ $detail->particular }}" class="form-control">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 mb-6">
                                <div class="form-group">
                                    <label for="quantity" class="control-label">Quantity</label> <br>
                                    <input type="number" name="quantity" value="{{ $detail->quantity }}" class="form-control">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="unit_price" class="control-label">Unit price</label> <br>
                                    <input type="number" name="unit_price" value="{{ $detail->unit_price }}" class="form-control">
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
