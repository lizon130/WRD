<div class="modal-header">
    <h5 class="modal-title" id="editModalLabel">Edit Manpower Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="editManpowerForm" action="{{ route('admin.manpower.update', $manpower->id) }}" method="POST">
        @csrf
        <div class="server_side_error mb-3"></div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date *</label>
                <input type="date" name="date" id="date" class="form-control"
                    value="{{ $manpower->date->format('Y-m-d') }}" required>
                <div class="invalid-feedback">Please select a date.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="unit" class="form-label">Unit *</label>
                <select name="unit" id="unit" class="form-control" required>
                    <option value="">Select Unit</option>
                    @foreach ($units as $unitOption)
                        <option value="{{ $unitOption }}" {{ $manpower->unit == $unitOption ? 'selected' : '' }}>
                            {{ $unitOption }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a unit.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="direct" class="form-label">Direct *</label>
                <input type="number" name="direct" id="direct" class="form-control" required min="0"
                    step="1" value="{{ $manpower->direct }}">
                <div class="invalid-feedback">Please enter direct count.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="indirect" class="form-label">Indirect *</label>
                <input type="number" name="indirect" id="indirect" class="form-control" required min="0"
                    step="1" value="{{ $manpower->indirect }}">
                <div class="invalid-feedback">Please enter indirect count.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="work_hours" class="form-label">Work Hours *</label>
                <input type="number" step="0.01" name="work_hours" id="work_hours" class="form-control" required
                    min="0" value="{{ $manpower->work_hours }}">
                <div class="invalid-feedback">Please enter work hours.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="smv" class="form-label">SMV (Standard Minute Value) *</label>
                <input type="number" step="0.01" name="smv" id="smv" class="form-control" required
                    min="0" value="{{ $manpower->smv }}">
                <div class="invalid-feedback">Please enter SMV.</div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="updateManpowerBtn" class="btn btn-primary">Update Data</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Form validation
        $('#editManpowerForm').on('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            $(this).addClass('was-validated');
        });
    });
</script>