<div class="modal-header">
    <h5 class="modal-title" id="editModalLabel">Edit Wash Report Entry</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="editEntryForm" action="{{ route('admin.wash-report-entry.update', $entry->id) }}" method="POST">
        @csrf
        <div class="server_side_error mb-3"></div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date *</label>
                <input type="date" name="date" id="date" class="form-control"
                    value="{{ $entry->date->format('Y-m-d') }}" required>
                <div class="invalid-feedback">Please select a date.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="unit" class="form-label">Unit *</label>
                <select name="unit" id="unit" class="form-control" required>
                    <option value="">Select Unit</option>
                    @foreach ($units as $unitOption)
                        <option value="{{ $unitOption }}" {{ $entry->unit == $unitOption ? 'selected' : '' }}>
                            {{ $unitOption }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a unit.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="FirstWashQty" class="form-label">First Wash Quantity</label>
                <input type="number" name="FirstWashQty" id="FirstWashQty" class="form-control" min="0"
                    step="1" value="{{ $entry->FirstWashQty }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="AcidWashQty" class="form-label">Acid Wash Quantity</label>
                <input type="number" name="AcidWashQty" id="AcidWashQty" class="form-control" min="0"
                    step="1" value="{{ $entry->AcidWashQty }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="FinalWashQty" class="form-label">Final Wash Quantity</label>
                <input type="number" name="FinalWashQty" id="FinalWashQty" class="form-control" min="0"
                    step="1" value="{{ $entry->FinalWashQty }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="ReWashQty" class="form-label">Re-Wash Quantity</label>
                <input type="number" name="ReWashQty" id="ReWashQty" class="form-control" min="0" step="1"
                    value="{{ $entry->ReWashQty }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="in_hand_balance" class="form-label">In Hand Balance</label>
                <input type="number" name="in_hand_balance" id="in_hand_balance" class="form-control" 
                    min="0" step="1" value="{{ $entry->in_hand_balance }}" placeholder="Enter in hand balance">
                <small class="text-muted">Current stock/inventory balance</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="rework_dry_proc" class="form-label">Rework Dry Process</label>
                <input type="number" name="rework_dry_proc" id="rework_dry_proc" class="form-control" 
                    min="0" step="0.01" value="{{ $entry->rework_dry_proc }}">
                <small class="text-muted">Enter decimal value (e.g., 10.50)</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="machine_work_hr" class="form-label">Machine Work Hours</label>
                <input type="number" name="machine_work_hr" id="machine_work_hr" class="form-control" 
                    min="0" max="72" step="0.01" value="{{ $entry->machine_work_hr }}" placeholder="Enter machine working hours">
                <small class="text-muted">Enter hours (max 72 hrs, e.g., 8.50, 12.75)</small>
            </div>

            {{-- <div class="col-md-6 mb-3">
                <label for="SewingLine" class="form-label">Sewing Line</label>
                <input type="text" name="SewingLine" id="SewingLine" class="form-control" maxlength="100"
                    value="{{ $entry->SewingLine }}">
            </div>

            <div class="col-md-12 mb-3">
                <label for="Remarks" class="form-label">Remarks</label>
                <textarea name="Remarks" id="Remarks" class="form-control" rows="3">{{ $entry->Remarks }}</textarea>
            </div> --}}
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="updateEntryBtn" class="btn btn-primary">Update Entry</button>
        </div>
    </form>
</div>