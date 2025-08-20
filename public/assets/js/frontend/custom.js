document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const steps = document.querySelectorAll('.step');
    const prevButton = document.querySelector('.previous');
    const nextButtons = document.querySelectorAll('.next');
    const submitButtons = document.querySelectorAll('.submit');
    const form = document.querySelector('form');
    const ServiceStaffCode = document.querySelector('.get_service_staff');

    // Ensure required elements exist
    if (!steps.length || !prevButton || !nextButtons.length || !submitButtons.length) return;

    // Utility: Check if value is empty
    function isEmpty(value) {
        return value === null || value === undefined || value.trim() === '';
    }

    // Show error message
    function showError(field, message, className) {
        let errorMessage = field.parentElement.querySelector(`.${className}`);
        if (!errorMessage) {
            errorMessage = document.createElement('p');
            errorMessage.classList.add(className, 'text-red-500', 'text-xs', 'mt-1');
            errorMessage.textContent = message;
            field.parentElement.appendChild(errorMessage);
        }
    }

    // Clear error message
    function clearError(field, className) {
        const errorMessage = field.parentElement.querySelector(`.${className}`);
        if (errorMessage) errorMessage.remove();
    }

    // Validate required fields in a step
    function validateRequiredFields(step) {
        const requiredFields = step.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (field.type === 'checkbox' || field.type === 'radio') {
                const inputs = step.querySelectorAll(`input[name="${field.name}"]`);
                const checked = Array.from(inputs).some(input => input.checked);
                if (!checked) {
                    field.classList.add('border-red-500');
                    showError(field, 'This field is required', `${field.type}-error-message`);
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    clearError(field, `${field.type}-error-message`);
                }
            } else if (field.type === 'email' && !field.checkValidity()) {
                field.classList.add('border-red-500');
                showError(field, 'Please enter a valid email address', 'error-message');
                isValid = false;
            } else if (isEmpty(field.value)) {
                field.classList.add('border-red-500');
                showError(field, 'This field is required', 'error-message');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
                clearError(field, 'error-message');
            }
        });

        return isValid;
    }

    // =========================
    // Booking Slots Validation
    // =========================
    function checkSlots() {
        const bookslotsInput = document.querySelector('#bookslots');
        const bookslotsValue = bookslotsInput ? bookslotsInput.value : '';
        const serviceInput = document.querySelector('#get_service_staff');
        const vendorInput = document.querySelector('#service_vendor_form');
        const errorElement = document.querySelector('.select-slots');
        const activeBtns = document.querySelectorAll('.next:visible, .submit:visible');

        let valid = true;

        // Only enforce slot selection if both service and vendor are selected
        if (serviceInput && vendorInput && !isEmpty(serviceInput.value) && !isEmpty(vendorInput.value)) {
            if (isEmpty(bookslotsValue)) {
                if (errorElement) errorElement.classList.remove('hidden');
                if (bookslotsInput) {
                    bookslotsInput.classList.add('border-red-500');
                }
                valid = false;
            } else {
                if (errorElement) errorElement.classList.add('hidden');
                if (bookslotsInput) {
                    bookslotsInput.classList.remove('border-red-500');
                }
                valid = true;
            }
        } else {
            // If either service or vendor is missing, don't require slots
            if (errorElement) errorElement.classList.add('hidden');
            if (bookslotsInput) {
                bookslotsInput.classList.remove('border-red-500');
            }
            valid = true;
        }

        // Enable/disable buttons
        activeBtns.forEach(btn => {
            btn.disabled = !valid;
        });

        return valid;
    }

    // =========================
    // Step Navigation
    // =========================
    function handleNextButtonClick() {
        const currentStepElement = steps[currentStep - 1];
        if (validateRequiredFields(currentStepElement) && checkSlots()) {
            if (currentStep < steps.length) {
                currentStepElement.style.display = 'none';
                steps[currentStep].style.display = 'block';
                currentStep++;
                prevButton.style.display = 'inline-block';
                if (currentStep === steps.length) {
                    nextButtons.forEach(btn => btn.style.display = 'none');
                    submitButtons.forEach(btn => btn.style.display = 'inline-block');
                }
                checkSlots(); // Re-validate after moving
            }
        }
    }

    function handlePreviousButtonClick() {
        nextButtons.forEach(btn => btn.style.display = 'inline-block');
        submitButtons.forEach(btn => btn.style.display = 'none');
        if (currentStep === 2) prevButton.style.display = 'none';
        if (currentStep > 1) {
            steps[currentStep - 1].style.display = 'none';
            steps[currentStep - 2].style.display = 'block';
            currentStep--;
            checkSlots();
        }
    }

    function handleSubmitButtonClick(event) {
        let isFormValid = true;
        steps.forEach(step => {
            if (!validateRequiredFields(step) || !checkSlots()) isFormValid = false;
        });
        if (!isFormValid) event.preventDefault();
    }

    // =========================
    // AJAX for service staff
    // =========================
    function get_services_staff() {
        const serviceId = ServiceStaffCode.value;
        $('.availibility, .calendar-wrap').addClass('hidden');

        $.ajax({
            url: '/get/services/staff',
            type: 'GET',
            data: { service_id: serviceId },
            dataType: 'json',
            success: function (response) {
                const staffSelect = document.querySelector('.service_vendor_form');
                const selectWrapper = document.querySelector('.select_service_vendor');

                staffSelect.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '---Select Vendor---';
                staffSelect.appendChild(defaultOption);

                if (response && response.length) {
                    staffSelect.disabled = false;
                    selectWrapper.classList.remove('hidden');
                    response.forEach(staff => {
                        const option = document.createElement('option');
                        option.value = staff.id;
                        option.textContent = staff.name;
                        staffSelect.appendChild(option);
                    });
                } else {
                    staffSelect.disabled = true;
                    selectWrapper.classList.add('hidden');
                    $('.calendar-wrap').addClass('hidden');
                    const noStaffOption = document.createElement('option');
                    noStaffOption.value = '';
                    noStaffOption.textContent = 'No staff available';
                    staffSelect.appendChild(noStaffOption);
                }
                checkSlots(); // Re-validate after staff load
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
            }
        });
    }

    // =========================
    // Slot click / input change
    // =========================
    document.addEventListener('click', function (e) {
        if (e.target.closest('.slot-card')) {
            const bookslotsInput = document.querySelector('#bookslots');
            if (bookslotsInput && !isEmpty(bookslotsInput.value)) {
                checkSlots(); // This will auto-hide error and enable buttons
            }
        }
    });

    // Listen for changes in service/vendor dropdowns
    document.addEventListener('change', function (e) {
        if (e.target.id === 'get_service_staff' || e.target.id === 'service_vendor_form') {
            checkSlots();
        }
    });

    // Also listen for input changes in #bookslots
    document.addEventListener('input', function (e) {
        if (e.target.id === 'bookslots') {
            checkSlots();
        }
    });

    // =========================
    // Initial Setup
    // =========================
    steps.forEach((step, index) => step.style.display = index === 0 ? 'block' : 'none');
    prevButton.style.display = 'none';
    submitButtons.forEach(btn => btn.style.display = 'none');
    document.querySelector('.select-slots')?.classList.add('hidden'); // Hide initially

    nextButtons.forEach(btn => btn.addEventListener('click', handleNextButtonClick));
    prevButton.addEventListener('click', handlePreviousButtonClick);
    submitButtons.forEach(btn => btn.addEventListener('click', handleSubmitButtonClick));
    ServiceStaffCode.addEventListener('change', get_services_staff);

    // Run initial validation
    checkSlots();
});