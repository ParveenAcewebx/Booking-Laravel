  $(document).ready(function() {
 /*============== staff validation form js  =========*/
    $('input').on('input', function() {
            const errorDiv = $(this).closest('.form-group').find('.error_message');

            // Remove the error message if it exists
            if (errorDiv.length) {
                errorDiv.remove();
            }
            $(this).removeClass('is-invalid');
        });
 /*==============  Assigned Services Select2  =========*/
        $('.assigned-services').select2({
            placeholder: "Select Services",
            allowClear: true,
            width: '100%'
        });
/*==============   date range picker staff day off ============*/
 $('.date-range-picker').each(function() {
        initializeDateRangePicker($(this));
    });

    $(document).on('focus', '.date-range-picker', function() {
        if (!$(this).data('daterangepicker')) {
            initializeDateRangePicker($(this));
            $(this).data('daterangepicker').show();
        }
    });

    function initializeDateRangePicker($element) {
        $element.daterangepicker({
            autoUpdateInput: false,
            locale: { format: 'MMMM D, YYYY' }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
    }
    /*==============   staff day off function show the staff day off data in the edit page day off tab   ============*/
     window.addDayOff = addDayOff;
    function addDayOff() {
        let container = document.getElementById('dayOffContainer');
        let html = `
        <div class="bg-gray-50 border rounded p-4 mb-2 day-off-item">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-gray-700">Day(s) Off</label>
                    <input type="text" name="day_offs[${dayOffIndex}][offs]" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Date Range</label>
                    <input type="text" name="day_offs[${dayOffIndex}][date]" class="w-full border rounded p-2 date-range-picker" required>
                </div>
            </div>
            <button type="button" class="delete-btn bg-red-500 text-white px-4 py-2 mt-4 rounded" 
            data-index="" onclick="deleteRow(this)">Delete</button>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);

        // Initialize new date-range-picker
        $('.date-range-picker').last().daterangepicker({
            autoUpdateInput: false,
            locale: { format: 'MMMM D, YYYY' }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });

        dayOffIndex++;
    }

    /*==============   Delete staff day off row ============*/
    function deleteRow(button){
        var row = button.closest('.day-off-item');
        if (row) {
            row.remove();
        }
    }
    window.deleteRow = deleteRow;
    /*==============   staff workday on chaange start time end time not select before start time  ============*/
    $(document).on('change', 'select[name^="working_days"][name$="[start]"]', function() {
        const $start = $(this);
        const selectedIndex = this.selectedIndex;
        const $end = $start.closest('div').find('select[name$="[end]"]');
        $end.find('option').prop('disabled', false);
        $end.find('option').each(function(index) {
            if (index <= selectedIndex) {
                $(this).prop('disabled', true);
            }
        });
    });


});
    