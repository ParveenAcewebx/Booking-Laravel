
$(document).ready(function () {

    /*============== staff validation form js  =========*/
    $('input').on('input', function () {
        const errorDiv = $(this).closest('.form-group').find('.error_message');
        if (errorDiv.length) errorDiv.remove();
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

    /*==============   staff day off dynamic add/remove  ============*/
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
               onclick="deleteRow(this)">Delete</button>
           </div>`;
        container.insertAdjacentHTML('beforeend', html);

        // re-init picker
        $('.date-range-picker').last().daterangepicker({
            autoUpdateInput: false,
            locale: { format: 'MMMM D, YYYY' }
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
        dayOffIndex++;
    }
    function deleteRow(button) {
        var row = button.closest('.day-off-item');
        if (row) row.remove();
    }
    window.deleteRow = deleteRow;

    /*==============   workday start-end validation ============*/
    $(document).on('change', 'select[name^="working_days"][name$="[start]"]', function () {
        const $start = $(this);
        const selectedIndex = this.selectedIndex;
        const $end = $start.closest('div').find('select[name$="[end]"]');
        $end.find('option').prop('disabled', false);
        $end.find('option').each(function (index) {
            if (index <= selectedIndex) $(this).prop('disabled', true);
        });
    });

    /*====================== cancel values ==================*/
    function populateCancellingValues(unit) {
        var $select = $('#cancelling_value');
        $select.empty();
        var max = (unit === 'hours') ? 24 : 30;
        var savedValue = $('#cancel_value').val();
        for (var i = 1; i <= max; i++) {
            var isSelected = (savedValue == i) ? 'selected' : '';
            $select.append('<option value="' + i + '" ' + isSelected + '>' + i + '</option>');
        }
    }
    var initialUnit = $('#cancelling_unit').val();
    populateCancellingValues(initialUnit);
    $('#cancelling_unit').on('change', function () {
        $('#cancel_value').val('');
        populateCancellingValues($(this).val());
    });

    /*====================== stripe mode ==================*/
    function toggleStripeOptions() {
        var paymentMode = $('#payment_mode').val();
        if (paymentMode === 'stripe') {
            $('.stripe-options').removeClass('hidden');
        } else {
            $('.stripe-options').addClass('hidden');
            $('.stripe-credentials').addClass('hidden');
        }
    }
    function toggleStripeCredentials() {
        const selected = $('input[name="payment_account"]:checked').val();
        if (selected === 'custom') {
            $('.stripe-credentials').removeClass('hidden');
        } else {
            $('.stripe-credentials').addClass('hidden');
        }
    }
    function toggleStripeMode() {
        if ($('#payment__is_live').is(':checked')) {
            $('.stripe-live').removeClass('d-none hidden');
            $('.stripe-test').addClass('d-none hidden');
        } else {
            $('.stripe-live').addClass('d-none hidden');
            $('.stripe-test').removeClass('d-none hidden');
        }
    }
    toggleStripeOptions();
    toggleStripeCredentials();
    toggleStripeMode();
    $('#payment_mode').on('change', function () {
        toggleStripeOptions(); toggleStripeCredentials();
    });
    $('input[name="payment_account"]').on('change', toggleStripeCredentials);
    $('#payment__is_live').on('change', toggleStripeMode);

    /*====================== delete gallery image (existing) ==================*/
    /*====================== Delete existing gallery images ==================*/
    window.deleteGalleryImage = function (button) {
        const container = button.closest('[data-img]');
        if (!container) return;

        // Get image path
        const imagePath = container.getAttribute('data-img');

        // Remove container visually
        container.remove();

        // Add hidden input to mark image for deletion
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_gallery[]';
        input.value = imagePath;

        // Append to the form
        document.querySelector('form').appendChild(input);
    }


    /*====================== New gallery uploads ==================*/
    let selectedGalleryFiles = [];

    $('#gallery-input').on('change', function (event) {
        const newFiles = Array.from(event.target.files);

        // Always ensure we have a preview container
        let previewContainer = document.getElementById('gallery-preview');
        if (!previewContainer) {
            previewContainer = document.createElement('div');
            previewContainer.id = 'gallery-preview';
            previewContainer.className = 'flex gap-2 mt-2 flex-wrap';
            document.querySelector('form').appendChild(previewContainer);
        }

        // Append new files without removing existing previews
        newFiles.forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const fileKey = file.name + "_" + file.size;
            selectedGalleryFiles.push({ key: fileKey, file });

            const reader = new FileReader();
            reader.onload = function (e) {
                const wrapper = document.createElement('div');
                wrapper.className = "relative w-16 h-16 inline-block mr-2 mb-2 new-preview";
                wrapper.setAttribute('data-key', fileKey);

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = "w-16 h-16 rounded shadow object-cover";

                const btn = document.createElement('button');
                btn.type = "button";
                btn.innerText = '✕';
                btn.className = "absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full";

                btn.onclick = function () {
                    wrapper.remove();
                    selectedGalleryFiles = selectedGalleryFiles.filter(f => f.key !== fileKey);
                    updateFileInput();
                };

                wrapper.appendChild(img);
                wrapper.appendChild(btn);
                previewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });

        // Reset the input to allow re-selecting the same file if needed
        this.value = "";
        updateFileInput();
    });

    // Function to update file input dynamically
    function updateFileInput() {
        const input = document.getElementById('gallery-input');
        const dataTransfer = new DataTransfer();

        selectedGalleryFiles.forEach(obj => dataTransfer.items.add(obj.file));
        input.files = dataTransfer.files;
    }


    /*====================== service featured image upload ==================*/
    let selectedFeatureFile = null;

    $('#feature-input').on('change', function (event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('new-feature-preview');
        selectedFeatureFile = null;

        if (!file || !file.type.startsWith('image/')) return;

        selectedFeatureFile = file;

        const reader = new FileReader();
        reader.onload = function (e) {
            // Look for an existing wrapper that is still visible
            let wrapper = [...previewContainer.querySelectorAll('.new-feature-wrapper, .existing-feature-wrapper')]
                .find(w => w.style.display !== "none");

            if (!wrapper) {
                // If no visible wrapper exists, create one
                wrapper = document.createElement('div');
                wrapper.className = "relative w-24 h-24 inline-block new-feature-wrapper";

                const img = document.createElement('img');
                img.className = "w-24 h-24 rounded shadow object-cover border";

                const btn = document.createElement('button');
                btn.type = "button";
                btn.innerText = '✕';
                btn.className = "absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full";

                btn.onclick = function () {
                    wrapper.style.display = "none"; // hide instead of remove
                    selectedFeatureFile = null;
                    document.getElementById('feature-input').value = "";

                    const removeFlag = wrapper.querySelector('.remove-thumbnail-flag');
                    if (removeFlag) removeFlag.value = 1;
                };

                wrapper.appendChild(img);
                wrapper.appendChild(btn);

                // Ensure hidden remove flag exists
                let removeFlag = wrapper.querySelector('.remove-thumbnail-flag');
                if (!removeFlag) {
                    removeFlag = document.createElement('input');
                    removeFlag.type = "hidden";
                    removeFlag.name = "remove_thumbnail";
                    removeFlag.value = 0;
                    removeFlag.className = "remove-thumbnail-flag";
                    wrapper.appendChild(removeFlag);
                }

                previewContainer.appendChild(wrapper);
            }

            // Update image preview
            const imgEl = wrapper.querySelector('img');
            imgEl.src = e.target.result;
            wrapper.style.display = "inline-block"; // make sure it's visible

            // Reset remove flag (because a new image was added)
            const removeFlag = wrapper.querySelector('.remove-thumbnail-flag');
            if (removeFlag) removeFlag.value = 0;
        };

        reader.readAsDataURL(file);
    });

    // Delete existing thumbnail
    document.querySelectorAll('.existing-delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const wrapper = this.closest('.existing-feature-wrapper');

            // Mark for removal
            const removeFlag = wrapper.querySelector('.remove-thumbnail-flag');
            if (removeFlag) removeFlag.value = 1;

            wrapper.style.display = "none"; // keep hidden, not removed
            document.getElementById('feature-input').value = "";
        });
    });




    /*====================== quill editor ==================*/
    const quill = new Quill('#editor', { theme: 'snow' });
    $('#description').val(quill.root.innerHTML);
    quill.on('text-change', function () {
        $('#description').val(quill.root.innerHTML);
    });
    $('form').on('submit', function () {
        $('#description').val(quill.root.innerHTML);
    });

});

