<!-- Edit Currency Modal -->
<div class="modal fade" id="editCurrencyModal" tabindex="-1" role="dialog" aria-labelledby="editCurrencyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCurrencyModalLabel">Change Currency</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-hidden="true">     
                </button>
      </div>
      <form action="{{ route('invoices.update', $invoice->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="transaction_currency">Currency</label>
            <select name="transaction_currency" class="form-control form-select" id="transaction_currency">
              <option value="USD" {{ $invoice->transaction_currency == 'USD' ? 'selected' : '' }}>USD</option>
              <option value="UGX" {{ $invoice->transaction_currency == 'UGX' ? 'selected' : '' }}>UGX</option>
              <!-- Add other currencies as needed -->
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
