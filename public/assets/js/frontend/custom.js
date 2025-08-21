document.addEventListener("DOMContentLoaded", function () {
    let currentSteps = 1;
    const steps = document.querySelectorAll('.step');
    const prevButton = document.querySelector('.previous');
    const nextButtons = document.querySelectorAll('.next');
    const submitButtons = document.querySelectorAll('.submit');
    const form = document.querySelector('form');
    const ServiceStaffCode = document.querySelector('.get_service_staff');

    console.log('ServiceStaffCode', ServiceStaffCode);

    if (!steps.length || !prevButton || !nextButtons.length || !submitButtons.length) {
        console.error('Required elements not found!');
        return; // Exit if elements aren't found
    }

    // Function to validate required fields
    function validateRequiredFields(step) {
        const requiredFields = step.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (field.type === 'checkbox') {
                const checkboxGroup = step.querySelectorAll(`input[name="${field.name}"]`);
                const checkedCheckboxes = Array.from(checkboxGroup).filter(checkbox => checkbox.checked);

                if (checkedCheckboxes.length === 0) {
                    field.classList.add('border-red-500');
                    let errorMessage = field.parentElement.querySelector('.checkbox-error-message');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.classList.add('checkbox-error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMessage);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    let errorMessage = field.parentElement.querySelector('.checkbox-error-message');
                    if (errorMessage) errorMessage.remove();
                }
            } else if (field.type === 'radio') {
                const radioGroup = step.querySelectorAll(`input[name="${field.name}"]`);
                const isChecked = Array.from(radioGroup).some(radio => radio.checked);

                if (!isChecked) {
                    field.classList.add('border-red-500');
                    let errorMessage = field.parentElement.querySelector('.radio-error-message');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.classList.add('radio-error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMessage);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    let errorMessage = field.parentElement.querySelector('.radio-error-message');
                    if (errorMessage) errorMessage.remove();
                }
            } else if (field.type === 'email') {
                if (!field.checkValidity()) {
                    field.classList.add('border-red-500');
                    let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                        ? field.nextElementSibling
                        : document.createElement('p');
                    if (!errorMessage.classList.contains('error-message')) {
                        errorMessage.classList.add('error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'Please enter a valid email address';
                        field.insertAdjacentElement('afterend', errorMessage);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                        ? field.nextElementSibling
                        : null;
                    if (errorMessage) errorMessage.remove();
                }
            } else if (!field.value.trim()) {
                field.classList.add('border-red-500');
                let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                    ? field.nextElementSibling
                    : document.createElement('p');
                if (!errorMessage.classList.contains('error-message')) {
                    errorMessage.classList.add('error-message', 'text-red-500', 'text-xs', 'mt-1');
                    errorMessage.textContent = 'This field is required';
                    field.insertAdjacentElement('afterend', errorMessage);
                }
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
                let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                    ? field.nextElementSibling
                    : null;
                if (errorMessage) errorMessage.remove();
            }
        });

        return isValid;
    }

    function handleNextButtonClick() {
        const currentStepElement = steps[currentSteps - 1];

        if (validateRequiredFields(currentStepElement)) {
            if (currentSteps < steps.length) {
                currentStepElement.style.display = 'none';
                steps[currentSteps].style.display = 'block';
                currentSteps++;

                prevButton.style.display = 'inline-block';

                if (currentSteps === steps.length) {
                    nextButtons.forEach(btn => btn.style.display = 'none');
                    submitButtons.forEach(btn => btn.style.display = 'inline-block');
                }
            }
        } else {
            return;
        }
    }

    function handlePreviousButtonClick() {
        nextButtons.forEach(btn => btn.style.display = 'inline-block');
        submitButtons.forEach(btn => btn.style.display = 'none');

        if (currentSteps === 2) {
            prevButton.style.display = 'none';
        }

        if (currentSteps > 1) {
            steps[currentSteps - 1].style.display = 'none';
            steps[currentSteps - 2].style.display = 'block';
            currentSteps--;
        }
    }

    function handleSubmitButtonClick(event) {
        let isFormValid = true;

        steps.forEach(step => {
            if (!validateRequiredFields(step)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault();
            // console.warn("Form validation failed!");
        }
    }

    function get_services_staff() {
        var serviceId = ServiceStaffCode.value;
        $('.availibility, .calendar-wrap').addClass('hidden');
        $.ajax({
            url: '/get/services/staff',
            type: 'GET',
            data: {
                service_id: serviceId
            },
            dataType: 'json',
            success: function (response) {
                console.log('Services Staff', response);
                var select_service_staff = document.querySelector('.select_service_vendor');
                var staffSelect = document.querySelector('.service_vendor_form');
                var calendarHidden = document.querySelector('.calendar-wrap');

                // Clear previous options
                staffSelect.innerHTML = '';

                if (response && response.length > 0) {
                    console.log('frontend Value', 1);

                    // Enable dropdown + show vendor field
                    staffSelect.disabled = false;
                    select_service_staff.classList.remove('hidden');

                    // Default option
                    var defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = '--- Select Vendor ---';
                    staffSelect.appendChild(defaultOption);

                    // Add each staff member
                    response.forEach(function (staff) {
                        var option = document.createElement('option');
                        option.value = staff.id;
                        option.textContent = staff.name;
                        staffSelect.appendChild(option);
                    });

                } else {
                    console.log('frontend Value', 0);

                    // Disable dropdown + show vendor field
                    staffSelect.disabled = true;
                    select_service_staff.classList.remove('hidden');
                    calendarHidden.classList.add('hidden');

                    // Show "No staff available"
                    var noStaffOption = document.createElement('option');
                    noStaffOption.value = '';
                    noStaffOption.textContent = 'No vendor available';
                    staffSelect.appendChild(noStaffOption);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
            }
        });
    }

    nextButtons.forEach(button => button.addEventListener('click', handleNextButtonClick));
    prevButton.addEventListener('click', handlePreviousButtonClick);
    submitButtons.forEach(button => button.addEventListener('click', handleSubmitButtonClick));
    ServiceStaffCode.addEventListener('change', get_services_staff);

    $(document).ready(function () {
        let formAction = document.querySelector('form').action;

        let urlParts = formAction.split('/');
        let formId = urlParts[urlParts.length - 1];
        $.ajax({
            url: '/get/session',
            method: 'GET',
            data: { formId: formId },
            success: function (response) {
                if (response.status === 'success') {
                    Object.keys(response['data']).forEach(function (key) {

                        let value = response['data'][key];
                        Object.keys(value).forEach(function (key) {

                            let formattedKey = key + "]";
                            let nestedValue = value[key];
                            let inputElement = $('input[name="' + formattedKey + '"]');
                            if (inputElement) {
                                inputElement.val(nestedValue);
                            }
                            let selectElement = $('select[name="' + formattedKey + '"]');
                            if (selectElement.length > 0) {
                                selectElement.val(nestedValue);
                                if (selectElement.hasClass('get_service_staff')) {
                                    get_services_staff();
                                }
                                if (selectElement.hasClass('service_vendor_form')) {
                                    setTimeout(function () {
                                        selectElement.val(nestedValue);
                                        selectElement.trigger('change');
                                    }, 1000);
                                }
                            }
                            let textareaElement = $('textarea[name="' + formattedKey + '"]');
                            if (textareaElement.length > 0) {
                                textareaElement.val(nestedValue);
                            }

                            if (key === 'bookslots') {
                                let inputElement = $('input[name="' + key + '"]');
                                if (inputElement) {
                                    inputElement.val(nestedValue.value);
                                    if (nestedValue.value) {
                                        const $wrapper = $('.slot-list-wrapper');
                                        const decodedValue = nestedValue.value.replace(/&quot;/g, '"');
                                        const slots = JSON.parse(decodedValue);
                                        slots.forEach((slot, index) => {
                                            const { date, price, start, end, duration, staff_ids } = slot;
                                            const uniqueKey = `slot-${staff_ids[0]}-${index}`;
                                            const slotHTML = `
                                        <div class="slot-item flex justify-between items-center gap-4 border border-gray-300 rounded-md p-3 bg-white shadow-sm text-sm w-full sm:w-full" data-slot="${uniqueKey}">
                                            <div class="font-medium text-gray-800 flex-1">
                                                <div>${date}</div>
                                                <input type='hidden' name='staff_id' value='${staff_ids.join(",")}'>
                                                <div class="text-xs text-gray-500">${start} → ${end}</div>
                                                <div class="text-xs text-gray-500">Duration: ${duration}</div>
                                            </div>
                                            <div class="text-green-600 font-semibold whitespace-nowrap">${price}</div>
                                            <div class="text-red-500 cursor-pointer remove-slot ml-auto">&#10006;</div>
                                        </div>`;

                                            // Append this slot HTML to the container (assuming you have a container to append it to)
                                            $($wrapper).append(slotHTML);
                                        });
                                        $('.remove-all-slots').removeClass('hidden');
                                    }
                                }
                            }

                        });
                    });
                }
            },
            error: function (error) {
                console.error('Error retrieving session data:', error);
            }
        });

    });

    window.onbeforeunload = function () {
        let formAction = document.querySelector('form').action;
        let urlParts = formAction.split('/');
        let formId = urlParts[urlParts.length - 1];
        let formElements = document.querySelector('form').elements;
        let dataToSave = {};
        Array.from(formElements).forEach(function (element) {
            if (element.name) {
                dataToSave[element.name] = {
                    name: element.name,
                    value: element.value
                };
            }
        });
        let finalDataToSave = {
            formId: formId,
            data: dataToSave
        };
        var headers = {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        };
        $.ajax({
            url: '/store/session',
            method: 'POST',
            data: finalDataToSave,
            headers: headers,
            success: function (response) {
                console.log('Data saved successfully');
            },
            error: function (error) {
                console.log('Error saving data');
            }
        });
    };
    function isEmpty(value) {
        return (
            value === undefined ||
            value === null ||
            (typeof value === "string" && value.trim() === "") ||
            (Array.isArray(value) && value.length === 0)
        );
    }

    function checkSlots($context) {
        const steps = $context.find('.step');
        const activeStep = steps.filter(':visible');
        // const currentStepIndex = steps.index(activeStep) + 1;

        // console.log("👉 Currently showing Step:", currentStepIndex);

        const bookslotsValue = $('#bookslots').val();
        const wrapper = activeStep.find('.slot-list-wrapper:visible');
        const serviceValue = $('#get_service_staff').val();
        const vendorValue = $('#service_vendor_form').val();
        const activeBtn = $context.find('.next:visible,.submit:visible');
        // console.log('activeBtn', activeBtn);
        let valid = true;
        const slotCount = wrapper.find('.slot-item').length;
        if (wrapper.length > 0 && !isEmpty(serviceValue) && !isEmpty(vendorValue) && isEmpty(bookslotsValue) && slotCount === 0) {
            // console.log(`❌ No booking slots selected (Step ${currentStepIndex})`);
            $('.select-slots').removeClass('hidden');
            valid = false;
        } else if (valid) {
            // console.log(`✅ Slot validation passed (Step ${currentStepIndex})`);
            $('.select-slots').addClass('hidden');
            valid = true;
        }
        activeBtn.prop('disabled', !valid);
    }

    // Run once on page load
    $(document).ready(function () {
        $('.form-navigation').each(function () {
            checkSlots($(this).closest('form, body'));
        });
    });

    // Recheck on actions
    $(document).on(
        'click change',
        '.slot-card, .add-slot, .remove-slot, .remove-all-slots, #get_service_staff, #service_vendor_form , .next,.submit,.previous',
        function () {
            const $section = $(this).closest('form, body');
            setTimeout(() => {
                checkSlots($section);
            }, 0);
        }
    );
});
