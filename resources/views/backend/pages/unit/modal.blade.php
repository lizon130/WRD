<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createUnitForm" action="{{ route('admin.unit.store') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Unit</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="server_side_error" role="alert">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="step step_1">
                                <div class="row">
                                    <div class="col-lg-12 form-group">
                                        <label for="unitName" class="form-label">Unit Name <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" name="unitName" class="form-control"
                                                placeholder="Enter unit name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="machineCount" class="form-label">Machine Count <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="machineCount" class="form-control"
                                                placeholder="Enter machine count" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="mgTarget" class="form-label">Management Target <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="mgTarget" class="form-control"
                                                placeholder="Enter Management Target" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="capacity_kg" class="form-label">Capacity (KG) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="capacity_kg" class="form-control"
                                                placeholder="Enter capacity in KG" min="0" step="0.01"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="piece_weight_gram" class="form-label">Piece Weight (Grams) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" name="piece_weight_gram" class="form-control"
                                                placeholder="Enter piece weight in grams" min="0" step="0.001"
                                                required>
                                            <span class="input-group-text">g</span>
                                        </div>
                                        <small class="text-muted">Enter the weight of one piece in grams (e.g., 600 for
                                            0.6kg)</small>
                                    </div>
                                    
                                    <!-- New Sewing Lines Field as Text Input -->
                                    <div class="col-lg-12 form-group">
                                        <label for="sewing_lines" class="form-label">Sewing Lines <span
                                                class="text-muted">(Optional)</span></label>
                                        <div class="input-group">
                                            <input type="text" name="sewing_lines" class="form-control"
                                                placeholder="Enter sewing lines (e.g., Line A, Line B)">
                                        </div>
                                        <small class="text-muted">Enter sewing line names or identifiers</small>
                                    </div>

                                    <div class="col-lg-12 form-group">
                                        <label class="form-label">Calculated Capacity (Pieces)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="calculated_pieces" readonly
                                                placeholder="Will be calculated automatically">
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
                    <button type="submit" id="createUnitBtn" class="btn btn-primary" data-check-area="modal-body">Add
                        Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>

<script>
    // Real-time calculation for create form
    $(document).ready(function() {
        function calculatePieces() {
            const capacityKg = parseFloat($('#createModal input[name="capacity_kg"]').val()) || 0;
            const pieceWeightGram = parseFloat($('#createModal input[name="piece_weight_gram"]').val()) || 0;

            if (pieceWeightGram > 0) {
                const pieceWeightKg = pieceWeightGram / 1000;
                const calculatedPieces = capacityKg / pieceWeightKg;
                $('#createModal #calculated_pieces').val(calculatedPieces.toFixed(0));
            } else {
                $('#createModal #calculated_pieces').val('0');
            }
        }

        // Bind events when modal is shown
        $('#createModal').on('shown.bs.modal', function() {
            $('#createModal input[name="capacity_kg"], #createModal input[name="piece_weight_gram"]')
                .on('input', calculatePieces);
        });

        // Reset calculated field when modal is hidden
        $('#createModal').on('hidden.bs.modal', function() {
            $('#createModal #calculated_pieces').val('');
            $('#createModal input[name="capacity_kg"], #createModal input[name="piece_weight_gram"]')
                .off('input', calculatePieces);
        });

        // Initial calculation if modal is already open
        if ($('#createModal').hasClass('show')) {
            calculatePieces();
        }
    });
</script>
