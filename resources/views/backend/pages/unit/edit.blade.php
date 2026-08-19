<!-- Edit Modal Content -->
<form id="editUnitForm" action="{{ route('admin.unit.update', $unit->id) }}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Edit Unit</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="server_side_error" role="alert"></div>
            </div>
            <div class="col-sm-12">
                <div class="step step_1">
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label for="unitName" class="form-label">Unit Name <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="unitName" class="form-control" value="{{ $unit->unitName }}"
                                    placeholder="Enter unit name" required>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="machineCount" class="form-label">Machine Count <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="machineCount" class="form-control"
                                    value="{{ $unit->machineCount }}" placeholder="Enter machine count" min="0"
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="mgTarget" class="form-label">Management Target <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="mgTarget" class="form-control" value="{{ $unit->mgTarget }}" placeholder="Enter management target" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="capacity_kg" class="form-label">Capacity (KG) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="capacity_kg" class="form-control" value="{{ $unit->capacity_kg }}" placeholder="Enter capacity in KG" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label for="piece_weight_gram" class="form-label">Piece Weight (Grams) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="piece_weight_gram" class="form-control" value="{{ $unit->piece_weight_gram }}" placeholder="Enter piece weight in grams" min="0" step="0.001" required>
                                <span class="input-group-text">g</span>
                            </div>
                        </div>

                        <!-- New Sewing Lines Field as Text Input in Edit Modal -->
                        <div class="col-lg-12 form-group">
                            <label for="sewing_lines" class="form-label">Sewing Lines <span class="text-muted">(Optional)</span></label>
                            <div class="input-group">
                                <input type="text" name="sewing_lines" class="form-control" value="{{ $unit->sewing_lines }}" placeholder="Enter sewing lines (e.g., Line A, Line B)">
                            </div>
                            <small class="text-muted">Enter sewing line names or identifiers</small>
                        </div>

                        <div class="col-lg-12 form-group">
                            <label class="form-label">Calculated Capacity (Pieces)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="edit_calculated_pieces" readonly
                                    value="{{ number_format($unit->capacity_pieces, 0) }}" placeholder="Auto-calculated">
                                <span class="input-group-text">pieces</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="editUnitBtn" class="btn btn-primary" data-check-area="modal-body">Update
            Unit</button>
    </div>
</form>