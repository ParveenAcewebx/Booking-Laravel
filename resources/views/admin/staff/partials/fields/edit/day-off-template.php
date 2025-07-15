<template id="dayOffTemplate">
    <div class="card border shadow-sm day-off-entry mb-3">
        <div class="card-body position-relative">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label class="font-weight-bold">Day(s) Off <span class="text-danger">*</span></label>
                    <input type="text" name="day_offs[__INDEX__][offs]" class="form-control" placeholder="e.g. Diwali, Festival, Leave">
                </div>
                <div class="form-group col-md-5">
                    <label class="font-weight-bold">Date Range <span class="text-danger">*</span></label>
                    <input type="text" name="day_offs[__INDEX__][date]" class="form-control date-range-picker" placeholder="Select Date Range">
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
    let dayOffIndex = 0;

    function addDayOffEntry(label = '', range = '') {
        const template = $('#dayOffTemplate').html().replace(/__INDEX__/g, dayOffIndex);
        const $entry = $(template);
        $('#dayOffRepeater').append($entry);

        const labelInput = $entry.find(`input[name="day_offs[${dayOffIndex}][offs]"]`);
        const dateInput = $entry.find(`input[name="day_offs[${dayOffIndex}][date]"]`);

        if (label) labelInput.val(label);
        if (range) dateInput.val(range);

        dateInput.daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'MMMM D, YYYY'
            }
        });

        dateInput.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });

        if (range) {
            const [start, end] = range.split(' - ');
            dateInput.data('daterangepicker').setStartDate(start);
            dateInput.data('daterangepicker').setEndDate(end);
            dateInput.val(range);
        }

        dayOffIndex++;
    }

    $('#addDayOffBtn').on('click', function() {
        const template = $('#dayOffTemplate').html().replace(/__INDEX__/g, dayOffIndex);
        const $entry = $(template);

        $('#dayOffRepeater').append($entry);
        dayOffIndex++;

        // Initialize date range picker
        $entry.find('.date-range-picker').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'MMMM D, YYYY'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
    });
    $(function() {
        const raw = $('#editDayOffData').val();
        if (raw) {
            try {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) {
                    parsed.forEach(item => {
                        if (item.label && item.range) {
                            addDayOffEntry(item.label, item.range);
                        }
                    });
                }
            } catch (err) {
                console.error('Invalid JSON in #editDayOffData', err);
            }
        }
    });
</script>