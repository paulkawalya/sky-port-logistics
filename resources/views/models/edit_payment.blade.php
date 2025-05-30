
<div id="edit_payment{{$payment->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Payment</h4>
                <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
            </div>
            <div class="modal-body p-8">
                <form action="{{ route('payments.update', $payment->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

                    <div class="row">
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="payment_date" class="control-label">Payment date</label> <br>
                                <input type="date" name="payment_date" value="{{ old('payment_date', $payment->payment_date) }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-16">
                            <div class="form-group">
                                <label for="amount_paid" class="control-label">Amount paid in {{ $invoice->transaction_currency }}</label> <br>
                                <input type="number" name="amount_paid" value="{{ old('amount_paid', $payment->amount_paid) }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="mode_of_payment" class="control-label">Mode of Payment</label> <br>
                                <select name="mode_of_payment" id="" class="form-control">
                                    @foreach($mode_of_payments as $mode_of_payment)
                                        <option value="{{ $mode_of_payment }}" {{ $payment->mode_of_payment == $mode_of_payment ? 'selected' : '' }}>
                                            {{ $mode_of_payment }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="narrative" class="control-label">Description</label> <br>
                                <input type="text" name="narrative" value="{{ old('narrative', $payment->narrative) }}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <button class="btn btn-danger" type="submit">Update</button>
                </form>

            </div>
        </div>
    </div>
</div>

