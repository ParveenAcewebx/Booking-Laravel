<div class="tab-pane fade show active" id="assigned-services" role="tabpanel">
    <button type="button" id="addServicesBtn" class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#servicesModal">
        <i class="feather icon-plus"></i> Add Services
    </button>
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="servicesTable">
            <thead class="bg-light">
                <tr>
                    <th class="text-left">Service</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Remove</th>
                </tr>
            </thead>
            <tbody>
               <tr id="noServiceRow">
                        <td colspan="3" class="text-center text-muted">No services assigned yet.</td>
                    </tr>
                {{-- Dynamic rows added here --}}
            </tbody>
        </table>
    </div>
    @include('admin.staff.partials.modals.services')
</div>