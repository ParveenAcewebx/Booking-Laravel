    <template id="staffTemplate">
        <div class="card border shadow-sm day-off-entry mb-3">
            <div class="card-body position-relative">
                <div class="form-row">
                    <!-- Staff Name -->
                    <div class="form-group col-md-5">
                        <label class="font-weight-bold">Staff <span class="text-danger">*</span></label>
                        <input type="text" name="select_staff[]" class="form-control staff-name" readonly>
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

<script>
    $(document).ready(function() {
        const staffData = $('#editDayOffData').val();
        let staffArray = [];

        try {
            staffArray = JSON.parse(staffData);
        } catch (e) {
            console.error('Invalid JSON from editDayOffData', e);
        }

        // Function to add staff entry
        function addStaffEntry(name) {
            const template = document.getElementById('staffTemplate').content.cloneNode(true);
            $(template).find('.staff-name').val(name); // Fill staff name

            // Initialize daterangepicker
            $(template).find('.date-range-picker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'MMMM D, YYYY'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });

            $('#dayOffRepeater').append(template);
        }

        // Pre-fill staff names from JSON
        if (Array.isArray(staffArray)) {
            staffArray.forEach(item => {
                if (item.user && item.user.name) {
                    addStaffEntry(item.user.name);
                }
            });
        }

        // Add button for new staff entries
        $('#addStaffButton').on('click', function() {
            addStaffEntry('');
        });
    });
</script>