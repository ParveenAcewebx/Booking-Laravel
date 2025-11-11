$(document).ready(function () {

    /*============== staff validation form js ==========*/
    $('input').on('input', function () {
        const errorDiv = $(this).closest('.form-group').find('.error_message');
        if (errorDiv.length) errorDiv.remove();
        $(this).removeClass('is-invalid');
    });

    /*============== Assigned Services Select2 ==========*/
    if ($('.assigned-services').length) {
        $('.assigned-services').select2({
            placeholder: "Select Services",
            allowClear: true,
            width: '100%'
        });
    }

    /*============== Date range picker staff day off ============*/
    function initializeDateRangePicker($element) {
        $element.daterangepicker({
            autoUpdateInput: false,
            locale: { format: 'MMMM D, YYYY' }
        }).on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
    }

    $('.date-range-picker').each(function () {
        initializeDateRangePicker($(this));
    });

    $(document).on('focus', '.date-range-picker', function () {
        if (!$(this).data('daterangepicker')) {
            initializeDateRangePicker($(this));
            $(this).data('daterangepicker').show();
        }
    });

    /*============== Staff day off dynamic add/remove ============*/
    let dayOffIndex = $('.day-off-item').length || 0; // track dynamic indices
    window.addDayOff = function () {
        const container = $('#dayOffContainer');
        if (!container.length) return;

        const html = `
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
                <button type="button" class="delete-btn bg-red-500 text-white px-4 py-2 mt-4 rounded">Delete</button>
            </div>`;
        container.append(html);

        initializeDateRangePicker(container.find('.date-range-picker').last());
        dayOffIndex++;
    };

    $(document).on('click', '.delete-btn', function () {
        $(this).closest('.day-off-item').remove();
    });

    /*============== Workday start-end validation ============*/
    $(document).on('change', 'select[name^="working_days"][name$="[start]"]', function () {
        const $start = $(this);
        const selectedIndex = this.selectedIndex;
        const $end = $start.closest('div').find('select[name$="[end]"]');
        $end.find('option').prop('disabled', false);
        $end.find('option').each(function (index) {
            if (index <= selectedIndex) $(this).prop('disabled', true);
        });
    });

    /*====================== Cancel values ==================*/
    function populateCancellingValues(unit) {
        const $select = $('#cancelling_value');
        if (!$select.length) return;
        $select.empty();
        const max = (unit === 'hours') ? 24 : 30;
        const savedValue = $('#cancel_value').val();
        for (let i = 1; i <= max; i++) {
            const isSelected = (savedValue == i) ? 'selected' : '';
            $select.append(`<option value="${i}" ${isSelected}>${i}</option>`);
        }
    }

    const initialUnit = $('#cancelling_unit').val();
    populateCancellingValues(initialUnit);
    $('#cancelling_unit').on('change', function () {
        $('#cancel_value').val('');
        populateCancellingValues($(this).val());
    });

    /*====================== Stripe mode ==================*/
    function toggleStripeOptions() {
        const paymentMode = $('#payment_mode').val();
        if (paymentMode === 'stripe') {
            $('.stripe-options').removeClass('hidden');
        } else {
            $('.stripe-options').addClass('hidden');
            $('.stripe-credentials').addClass('hidden');
        }
    }

    function toggleStripeCredentials() {
        const selected = $('input[name="payment_account"]:checked').val();
        if (selected === 'custom') $('.stripe-credentials').removeClass('hidden');
        else $('.stripe-credentials').addClass('hidden');
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
    $('#payment_mode').on('change', () => { toggleStripeOptions(); toggleStripeCredentials(); });
    $('input[name="payment_account"]').on('change', toggleStripeCredentials);
    $('#payment__is_live').on('change', toggleStripeMode);

    /*====================== Delete existing gallery images ==================*/
    window.deleteGalleryImage = function (button) {
        const wrapper = $(button).closest('.existing-gallery-wrapper');
        if (!wrapper.length) return;
        const hiddenInput = wrapper.find('input[name="existing_gallery[]"]');
        const imagePath = hiddenInput.length ? hiddenInput.val() : null;

        if (imagePath) {
            $('<input>').attr({
                type: 'hidden',
                name: 'delete_gallery[]',
                value: imagePath
            }).appendTo('form');
        }

        wrapper.remove();
    };

    /*====================== Handle new gallery uploads ==================*/
    const galleryInput = $('#gallery-input');
    let selectedGalleryFiles = [];
    if (galleryInput.length) {
        galleryInput.on('change', function (event) {
            const newFiles = Array.from(event.target.files);
            newFiles.forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const fileKey = file.name + "_" + file.size;
                selectedGalleryFiles.push({ key: fileKey, file });

                const reader = new FileReader();
                reader.onload = function (e) {
                    const wrapper = $('<div class="relative w-16 h-16 inline-block mr-2 mb-2 new-preview"></div>').attr('data-key', fileKey);
                    const img = $('<img>').attr('src', e.target.result).addClass('w-16 h-16 rounded shadow object-cover');
                    const btn = $('<button type="button" class="absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full">✕</button>');
                    btn.on('click', function () {
                        wrapper.remove();
                        selectedGalleryFiles = selectedGalleryFiles.filter(f => f.key !== fileKey);
                        updateFileInput();
                    });
                    wrapper.append(img, btn);
                    $('#gallery-preview').append(wrapper);
                };
                reader.readAsDataURL(file);
            });

            this.value = "";
            updateFileInput();

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedGalleryFiles.forEach(obj => dataTransfer.items.add(obj.file));
                galleryInput[0].files = dataTransfer.files;
            }
        });
    }

    /*====================== Service featured image upload ==================*/
    const featureInput = $('#feature-input');
    if (featureInput.length) {
        let selectedFeatureFile = null;
        const previewContainer = $('#new-feature-preview');

        featureInput.on('change', function (event) {
            const file = event.target.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            selectedFeatureFile = file;

            const reader = new FileReader();
            reader.onload = function (e) {
                let wrapper = previewContainer.find('.new-feature-wrapper, .existing-feature-wrapper').filter(function () {
                    return $(this).css('display') !== 'none';
                }).first();

                if (!wrapper.length) {
                    wrapper = $('<div class="relative w-24 h-24 inline-block new-feature-wrapper"></div>');
                    const img = $('<img class="w-24 h-24 rounded shadow object-cover border">');
                    const btn = $('<button type="button" class="absolute top-0 right-0 bg-red-600 text-white text-xs px-1 rounded-full">✕</button>');
                    btn.on('click', function () {
                        wrapper.hide();
                        selectedFeatureFile = null;
                        featureInput.val('');
                        wrapper.find('.remove-thumbnail-flag').val(1);
                    });
                    wrapper.append(img, btn);

                    if (!wrapper.find('.remove-thumbnail-flag').length) {
                        const removeFlag = $('<input type="hidden" name="remove_thumbnail" value="0" class="remove-thumbnail-flag">');
                        wrapper.append(removeFlag);
                    }
                    previewContainer.append(wrapper);
                }

                wrapper.find('img').attr('src', e.target.result);
                wrapper.show();
                wrapper.find('.remove-thumbnail-flag').val(0);
            };
            reader.readAsDataURL(file);
        });
    }

    // Delete existing thumbnail
    $('.existing-delete-btn').on('click', function () {
        const wrapper = $(this).closest('.existing-feature-wrapper');
        wrapper.find('.remove-thumbnail-flag').val(1);
        wrapper.hide();
        if (featureInput.length) featureInput.val('');
    });

    /*====================== Quill editor ==================*/
    const quillEl = $('#editor');
    if (quillEl.length) {
        const quill = new Quill('#editor', { theme: 'snow' });
        $('#description').val(quill.root.innerHTML);
        quill.on('text-change', function () {
            $('#description').val(quill.root.innerHTML);
        });
        $('form').on('submit', function () {
            $('#description').val(quill.root.innerHTML);
        });
    }

});
