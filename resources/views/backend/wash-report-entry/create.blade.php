<div class="modal-header">
    <h5 class="modal-title" id="createModalLabel">Add New Wash Report Entry</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="createEntryForm" action="{{ route('admin.wash-report-entry.store') }}" method="POST">
        @csrf
        <div class="server_side_error mb-3"></div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date *</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}"
                    required>
                <div class="invalid-feedback">Please select a date.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="unit" class="form-label">Unit *</label>
                <select name="unit" id="unit" class="form-control" required>
                    <option value="">Select Unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a unit.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="FirstWashQty" class="form-label">First Wash Quantity</label>
                <input type="number" name="FirstWashQty" id="FirstWashQty" class="form-control" min="0"
                    step="1" value="0">
                <small class="text-muted">Leave empty or 0 if not applicable</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="AcidWashQty" class="form-label">Acid Wash Quantity</label>
                <input type="number" name="AcidWashQty" id="AcidWashQty" class="form-control" min="0"
                    step="1" value="0">
                <small class="text-muted">Leave empty or 0 if not applicable</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="FinalWashQty" class="form-label">Final Wash Quantity</label>
                <input type="number" name="FinalWashQty" id="FinalWashQty" class="form-control" min="0"
                    step="1" value="0">
                <small class="text-muted">Leave empty or 0 if not applicable</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="ReWashQty" class="form-label">Re-Wash Quantity</label>
                <input type="number" name="ReWashQty" id="ReWashQty" class="form-control" min="0" step="1"
                    value="0">
                <small class="text-muted">Leave empty or 0 if not applicable</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="in_hand_balance" class="form-label">In Hand Balance</label>
                <input type="number" name="in_hand_balance" id="in_hand_balance" class="form-control" 
                    min="0" step="1" value="0" placeholder="Enter in hand balance">
                <small class="text-muted">Current stock/inventory balance</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="rework_dry_proc" class="form-label">Rework Dry Process</label>
                <input type="number" name="rework_dry_proc" id="rework_dry_proc" class="form-control" 
                    min="0" step="0.01" value="0.00">
                <small class="text-muted">Enter decimal value (e.g., 10.50)</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="machine_work_hr" class="form-label">Machine Work Hours</label>
                <input type="number" name="machine_work_hr" id="machine_work_hr" class="form-control" 
                    min="0" max="72" step="0.01" value="0.00" placeholder="Enter machine working hours">
                <small class="text-muted">Enter hours (max 72 hrs, e.g., 8.50, 12.75)</small>
            </div>

            {{-- <div class="col-md-6 mb-3">
                <label for="SewingLine" class="form-label">Sewing Line</label>
                <input type="text" name="SewingLine" id="SewingLine" class="form-control" maxlength="100">
            </div> --}}

            {{-- <div class="col-md-12 mb-3">
                <label for="Remarks" class="form-label">Remarks</label>
                <textarea name="Remarks" id="Remarks" class="form-control" rows="3"></textarea>
            </div> --}}
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="createEntryBtn" class="btn btn-primary">Save Entry</button>
        </div>
    </form>
</div>