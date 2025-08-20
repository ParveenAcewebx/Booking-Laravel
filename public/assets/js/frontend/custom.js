document.addEventListener("DOMContentLoaded", function () {
    let currentSteps = 1;
    const steps = document.querySelectorAll('.step');
    const prevButton = document.querySelector('.previous');
    const nextButtons = document.querySelectorAll('.next');
    const submitButtons = document.querySelectorAll('.submit');
    const form = document.querySelector('form');
    const ServiceStaffCode = document.querySelector('.get_service_staff');

    if (!steps.length || !prevButton || !nextButtons.length || !submitButtons.length) {
        console.error('Required elements not found!');
        return;
    }

    // Utility
    function isEmpty(value) {
        return (
            value === undefined ||
            value === null ||
            (typeof value === "string" && value.trim() === "") ||
            (Array.isArray(value) && value.length === 0)
        );
    }

    // ✅ Validation for fields
    function validateRequiredFields(step) {
        const requiredFields = step.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            let errorMessage;

            if (field.type === 'checkbox') {
                const group = step.querySelectorAll(`input[name="${field.name}"]`);
                const checked = [...group].some(chk => chk.checked);

                if (!checked) {
                    field.classList.add('border-red-500');
                    errorMessage = field.parentElement.querySelector('.checkbox-error-message');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.classList.add('checkbox-error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMessage);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    errorMessage = field.parentElement.querySelector('.checkbox-error-message');
                    if (errorMessage) errorMessage.remove();
                }
            } else if (field.type === 'radio') {
                const group = step.querySelectorAll(`input[name="${field.name}"]`);
                const checked = [...group].some(r => r.checked);

                if (!checked) {
                    field.classList.add('border-red-500');
                    errorMessage = field.parentElement.querySelector('.radio-error-message');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.classList.add('radio-error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMessage);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    errorMessage = field.parentElement.querySelector('.radio-error-message');
                    if (errorMessage) errorMessage.remove();
                }
            } else if (field.type === 'email') {
                if (!field.checkValidity()) {
                    field.classList.add('border-red-500');
                    errorMessage = field.nextElementSibling?.classList.contains('error-message')
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
                    errorMessage = field.nextElementSibling?.classList.contains('error-message')
                        ? field.nextElementSibling
                        : null;
                    if (errorMessage) errorMessage.remove();
                }
            } else if (!field.value.trim()) {
                field.classList.add('border-red-500');
                errorMessage = field.nextElementSibling?.classList.contains('error-message')
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
                errorMessage = field.nextElementSibling?.classList.contains('error-message')
                    ? field.nextElementSibling
                    : null;
                if (errorMessage) errorMessage.remove();
            }
        });

        return isValid;
    }

    // ✅ Navigation handlers
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
            console.warn("Form validation failed!");
        }
    }

    // ✅ Fetch staff
    function get_services_staff() {
        const serviceId = ServiceStaffCode.value;
        document.querySelectorAll('.availibility, .calendar-wrap').forEach(el => el.classList.add('hidden'));

        fetch(`/get/services/staff?service_id=${encodeURIComponent(serviceId)}`)
            .then(res => res.json())
            .then(response => {
                const select_service_staff = document.querySelector('.select_service_vendor');
                const staffSelect = document.querySelector('.service_vendor_form');
                const calendarHidden = document.querySelector('.calendar-wrap');

                staffSelect.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '---Select Vendor---';
                staffSelect.appendChild(defaultOption);

                if (response && response.length > 0) {
                    staffSelect.disabled = false;
                    select_service_staff.classList.remove('hidden');
                    response.forEach(staff => {
                        const option = document.createElement('option');
                        option.value = staff.id;
                        option.textContent = staff.name;
                        staffSelect.appendChild(option);
                    });
                } else {
                    staffSelect.disabled = true;
                    select_service_staff.classList.add('hidden');
                    calendarHidden.classList.add('hidden');

                    const noStaffOption = document.createElement('option');
                    noStaffOption.value = '';
                    noStaffOption.textContent = 'No staff available';
                    staffSelect.appendChild(noStaffOption);
                }
            })
            .catch(err => console.error("AJAX error:", err));
    }

    // ✅ Slot validation
    function checkSlots(context) {
        const activeStep = [...context.querySelectorAll('.step')].find(step => step.offsetParent !== null);

        const bookslotsValue = document.querySelector('#bookslots')?.value;
        const wrapper = activeStep ? activeStep.querySelector('.slot-list-wrapper') : null;
        const serviceValue = document.querySelector('#get_service_staff')?.value;
        const vendorValue = document.querySelector('#service_vendor_form')?.value;

        const activeBtn = [...context.querySelectorAll('.next, .submit')].find(
            btn => btn.offsetParent !== null
        );

        let valid = true;
        const slotCount = wrapper ? wrapper.querySelectorAll('.slot-item').length : 0;

        if (wrapper && !isEmpty(serviceValue) && !isEmpty(vendorValue) && isEmpty(bookslotsValue) && slotCount === 0) {
            document.querySelector('.select-slots').classList.remove('hidden'); // show error
            valid = false;
        } else {
            document.querySelector('.select-slots').classList.add('hidden'); // hide error
            valid = true;
        }

        if (activeBtn) activeBtn.disabled = !valid;
    }

    // ✅ Event bindings
    nextButtons.forEach(button => button.addEventListener('click', handleNextButtonClick));
    prevButton.addEventListener('click', handlePreviousButtonClick);
    submitButtons.forEach(button => button.addEventListener('click', handleSubmitButtonClick));
    ServiceStaffCode.addEventListener('change', get_services_staff);

    // ✅ Initial check
    checkSlots(document);

    // Recheck on slot or nav clicks
    document.addEventListener('click', e => {
        if (e.target.closest('.slot-card, .add-slot, .remove-slot, .remove-all-slots, #get_service_staff, #service_vendor_form, .next, .submit, .previous')) {
            // small delay so DOM updates first
            setTimeout(() => checkSlots(document), 50);
        }
    });
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
});
