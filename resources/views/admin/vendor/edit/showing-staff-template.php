<template id="staffTemplate">
    <div class="card border shadow-sm day-off-entry mb-3">
        <div class="card-body position-relative">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label class="font-weight-bold">Day(s) Off <span class="text-danger">*</span></label>
                    <input type="text" name="select_staff[] " class="form-control" placeholder="">
                </div>
                <div class="form-group col-md-5">
                    <label class="font-weight-bold">Date Range <span class="text-danger">*</span></label>
                    <input type="text" name="date_ranges[]" class="form-control date-range-picker" placeholder="Select Date Range">
                </div>
            </div>
            <button type="button"
                class="btn btn-sm btn-outline-danger position-absolute"
                style="top: 10px; right: 10px;"
                onclick="this.closest('.day-off-entry').remove();">
                <i class="feather icon-trash-2"></i>
            </button>
        </div>
    </div>
</template>
