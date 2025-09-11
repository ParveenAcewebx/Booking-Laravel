$(document).ready(function () {
    /*============== staff validation form js  =========*/
    $('input').on('input', function () {
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
    $('.date-range-picker').each(function () {
        initializeDateRangePicker($(this));
    });

    $(document).on('focus', '.date-range-picker', function () {
        if (!$(this).data('daterangepicker')) {
            initializeDateRangePicker($(this));
            $(this).data('daterangepicker').show();
        }
    });

    function initializeDateRangePicker($element) {
        $element.daterangepicker({
            autoUpdateInput: false,
            locale: { format: 'MMMM D, YYYY' }
        }).on('apply.daterangepicker', function (ev, picker) {
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
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });

        dayOffIndex++;
    }

    /*==============   Delete staff day off row ============*/
    function deleteRow(button) {
        var row = button.closest('.day-off-item');
        if (row) {
            row.remove();
        }
    }
    window.deleteRow = deleteRow;
    /*==============   staff workday on chaange start time end time not select before start time  ============*/
    $(document).on('change', 'select[name^="working_days"][name$="[start]"]', function () {
        const $start = $(this);
        const selectedIndex = this.selectedIndex;
        const $end = $start.closest('div').find('select[name$="[end]"]');
        $end.find('option').prop('disabled', false);
        $end.find('option').each(function (index) {
            if (index <= selectedIndex) {
                $(this).prop('disabled', true);
            }
        });
    });
    /*====================== service tab in setting ==================*/
    function populateCancellingValues(unit) {
        var $valueSelect = $('#cancelling_value');
        var selectedValue = $('#cancel_value').val();
        $valueSelect.empty();

        var max = unit === 'days' ? 30 : 24;

        for (var i = 1; i <= max; i++) {
            $valueSelect.append($('<option>', {
                value: i,
                text: i
            }));
        }

        if (selectedValue && selectedValue <= max) {
            $valueSelect.val(selectedValue);
        }
    }

    // Initial population based on selected unit
    var initialUnit = $('#cancelling_unit').val();
    populateCancellingValues(initialUnit);

    // On change of unit
    $('#cancelling_unit').on('change', function () {
        var selectedUnit = $(this).val();
        populateCancellingValues(selectedUnit);
    });

    $(document).ready(function () {
        function toggleStripeOptions() {
            var paymentMode = $('#payment_mode').val();

            if (paymentMode === 'stripe') {
                $('.stripe-options').removeClass('hidden');
            } else {
                $('.stripe-options').addClass('hidden');
                $('.stripe-credentials').addClass('hidden');
            }
        }

        // Initial check on page load
        toggleStripeOptions();

        // On change of payment mode
        $('#payment_mode').on('change', function () {
            toggleStripeOptions();
            toggleStripeCredentials();
        });

        function toggleStripeCredentials() {
            const selected = $('input[name="payment_account"]:checked').val();
            if (selected === 'custom') {
                $('.stripe-credentials').removeClass('hidden');
            } else {
                $('.stripe-credentials').addClass('hidden');
            }
        }
        toggleStripeCredentials();
        $('input[name="payment_account"]').on('change', function () {
            toggleStripeCredentials();
        });
        $('#payment__is_live').on('change', function () {
            if ($(this).is(':checked')) {
                $('.stripe-live').removeClass('d-none');
                $('.stripe-test').addClass('d-none');
            } else {
                $('.stripe-live').addClass('d-none');
                $('.stripe-test').removeClass('d-none');
            }
        });
    });
    function toggleStripeMode() {
        if ($('#payment__is_live').is(':checked')) {
            $('.stripe-live').removeClass('d-none').removeClass('hidden');
            $('.stripe-test').addClass('d-none').addClass('hidden');
        } else {
            $('.stripe-live').addClass('d-none').addClass('hidden');
            $('.stripe-test').removeClass('d-none').removeClass('hidden');
        }
    }
    toggleStripeMode();
    $('#payment__is_live').on('change', function () {
        toggleStripeMode();
    });

    // delete gallery image in service tab 
    function deleteGalleryImage(button) {
        const container = button.closest('[data-img]');
        const imagePath = container.getAttribute('data-img');

        // Remove the image preview block
        container.remove();

        // Append hidden input to mark it for deletion
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_gallery[]';
        input.value = imagePath;

        document.querySelector('form').appendChild(input);
    }
    window.deleteGalleryImage = deleteGalleryImage;
    let selectedFiles = [];

    // Handle preview + delete of new images
    document.getElementById('gallery-input').addEventListener('change', function (event) {
        const previewContainer = document.getElementById('gallery-preview');
        const files = Array.from(event.target.files);

        selectedFiles = files; // Store files for submission

        // Remove previous previews of new images
        document.querySelectorAll('.new-preview').forEach(el => el.remove());

        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('relative', 'w-16', 'h-16', 'new-preview');
                    wrapper.setAttribute('data-index', index);

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('w-16', 'h-16', 'rounded', 'shadow', 'object-cover');

                    const delBtn = document.createElement('button');
                    delBtn.type = 'button';
                    delBtn.innerText = '✕';
                    delBtn.classList.add('absolute', 'top-0', 'right-0', 'bg-red-600', 'text-white', 'text-xs', 'px-1', 'rounded-full');
                    delBtn.onclick = function () {
                        wrapper.remove();
                        removeSelectedFile(index);
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(delBtn);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            }
        });

    });

    // Remove file from input selection
    function removeSelectedFile(indexToRemove) {
        selectedFiles.splice(indexToRemove, 1);

        const fileInput = document.getElementById('gallery-input');
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }
    // add service for gallery validation 
    let selectedGalleryFiles = [];
    document.getElementById('gallery-input').addEventListener('change', function (event) {
        const newFiles = Array.from(event.target.files);
        const previewContainer = document.getElementById('new-gallery-preview');

        // Merge new files into existing ones
        newFiles.forEach(file => {
            if (!file.type.startsWith('image/')) return;

            // Check if file already added (by name + size)
            const alreadyExists = selectedGalleryFiles.some(f => f.name === file.name && f.size === file.size);
            if (!alreadyExists) {
                selectedGalleryFiles.push(file);

                const reader = new FileReader();
                reader.onload = function (e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = "relative w-16 h-16";
                    wrapper.setAttribute('data-file', file.name + file.size); // Unique identifier

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = "w-16 h-16 rounded shadow object-cover";

                    const btn = document.createElement('button');
                    btn.type = "button";
                    btn.innerText = '✕';
                    btn.className = "absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full";
                    btn.onclick = function () {
                        wrapper.remove();
                        // Remove file from array
                        selectedGalleryFiles = selectedGalleryFiles.filter(f => f.name + f.size !== file.name + file.size);
                        updateFileInput();
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(btn);
                    previewContainer.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            }
        });

        // Reset file input (so selecting the same file again triggers change)
        this.value = "";
        updateFileInput();
    });

    // Function to update input's FileList with current selected files
    function updateFileInput() {
        const input = document.getElementById('gallery-input');
        const dataTransfer = new DataTransfer();

        selectedGalleryFiles.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }
});

