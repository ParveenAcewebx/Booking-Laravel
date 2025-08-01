<template id="staffTemplate">
    <div class="card border shadow-sm day-off-entry mb-3">
        <div class="card-body position-relative">
            <div class="form-row pt-2">
                <div class="form-group col-md-5 mb-1">
                    <select name="select_staff[]" class="form-control select-user">
                        <option value="">--- Please Select Staff ---</option>
                        @foreach($availableStaff as $staff)
                        <option value="{{ $staff->id }}"
                            data-primary-staff="{{ $staff->staff->primary_staff ?? 0 }}">
                            {{ $staff->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6 addServices">
                    <!-- Services will load here -->
                </div>
            </div>
            <button type="button"
                class="btn btn-sm btn-outline-danger position-absolute delete-row"
                style="top: 10px; right: 10px;">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button"
                class="btn btn-sm btn-outline-success position-absolute verify-row primary-badge-container"
                style="top: 10px; right: 10px; display:none; pointer-events: none; cursor: default;"
                data-toggle="tooltip"
                title="Primary Staff">
                <i class="feather icon-check"></i>
            </button>
        </div>
    </div>
</template>