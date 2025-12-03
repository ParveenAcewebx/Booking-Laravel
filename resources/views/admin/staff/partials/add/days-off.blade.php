<div class="tab-pane fade" id="days-off" role="tabpanel">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 font-weight-bold">Day Offs</h6>
                <button type="button" class="btn btn-sm btn-primary" id="addDayOffBtn">
                    <i class="feather icon-plus"></i> Add Day Off
                </button>
            </div>
            <div id="dayOffRepeater"></div>
            @include('admin.staff.partials.fields.add.day-off-template')
        </div>
    </div>
</div>