@isset($payment)
<div class="modal fade" id="editPaymentModal{{$payment->id}}" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel{{$payment->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPaymentModalLabel{{$payment->id}}">Edit Payment</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('quotation_payments.update', $payment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="quotation_id" value="{{ $payment->quotation_id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Amount Paid</label>
                        <input type="number" step="0.01" class="form-control" id="amount_paid" name="amount_paid" value="{{ $payment->amount_paid }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="mode_of_payment" class="form-label">Payment Method</label>
                        <select class="form-select" id="mode_of_payment" name="mode_of_payment" required>
                            <option value="Cash" {{ $payment->mode_of_payment == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Bank Transfer" {{ $payment->mode_of_payment == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Mobile Money" {{ $payment->mode_of_payment == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="Cheque" {{ $payment->mode_of_payment == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="narrative" class="form-label">Narrative (Optional)</label>
                        <input type="text" class="form-control" id="narrative" name="narrative" value="{{ $payment->narrative }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endisset
